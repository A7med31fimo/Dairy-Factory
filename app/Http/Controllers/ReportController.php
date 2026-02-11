<?php

namespace App\Http\Controllers;

use App\Helpers\ArabicPdf;
use App\Models\MilkCollection;
use App\Models\Production;
use App\Models\Distribution;
use App\Models\Debt;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'period'     => 'required|in:today,week,month,custom',
            'date_from'  => 'required_if:period,custom|date|nullable',
            'date_to'    => 'required_if:period,custom|date|nullable',
        ], [
            'period.required'       => 'الفترة الزمنية مطلوبة',
            'date_from.required_if' => 'تاريخ البداية مطلوب',
            'date_to.required_if'   => 'تاريخ النهاية مطلوب',
        ]);

        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $data = $this->gatherReportData($dateFrom, $dateTo);
        $data['period']       = $request->period;
        $data['date_from']    = $dateFrom;
        $data['date_to']      = $dateTo;
        $data['period_label'] = $this->getPeriodLabel($request->period, $dateFrom, $dateTo);

        return view('reports.show', $data);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'period'    => 'required|in:today,week,month,custom',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
        ]);

        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $data = $this->gatherReportData($dateFrom, $dateTo);
        $data['period']       = $request->period;
        $data['date_from']    = $dateFrom;
        $data['date_to']      = $dateTo;
        $data['period_label'] = $this->getPeriodLabel($request->period, $dateFrom, $dateTo);

        // Reshape all Arabic text fields for correct PDF rendering
        $data = $this->reshapeForPdf($data);

        $pdf = Pdf::loadView('reports.pdf', $data)
            ->setPaper('a4')
            ->setOptions([
                'defaultFont'             => 'dejavu sans',
                'isHtml5ParserEnabled'    => true,
                'isRemoteEnabled'         => false,
                'isFontSubsettingEnabled' => true,
                'chroot'                  => public_path(),
                'dpi'                     => 150,
            ]);

        $filename = 'report_' . $dateFrom->format('Y-m-d') . '_' . $dateTo->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Reshape all Arabic strings in the data array for PDF rendering.
     * dompdf doesn't handle Arabic text shaping natively — this converts
     * base Arabic characters to their correct joined presentation forms.
     */
    private function reshapeForPdf(array $data): array
    {
        // Reshape the period label
        $data['period_label'] = ArabicPdf::reshape($data['period_label']);

        // Reshape milk collections
        foreach ($data['milkCollections'] as $item) {
            $item->farmer_name    = ArabicPdf::reshape($item->farmer_name);
            $item->driver_name    = ArabicPdf::reshape($item->driver_name);
            $item->vehicle_number = ArabicPdf::reshape($item->vehicle_number);
            if ($item->notes) {
                $item->notes = ArabicPdf::reshape($item->notes);
            }
        }

        // Reshape productions
        $ptypes = Production::productTypes();
        foreach ($data['productions'] as $item) {
            $item->product_name = ArabicPdf::reshape($item->product_name);
            $item->product_type_label = ArabicPdf::reshape($ptypes[$item->product_type] ?? $item->product_type);
            $item->unit = ArabicPdf::reshape($item->unit);
        }

        // Reshape distributions
        foreach ($data['distributions'] as $dist) {
            $dist->shop_name    = ArabicPdf::reshape($dist->shop_name);
            $dist->driver_name  = ArabicPdf::reshape($dist->driver_name);
            foreach ($dist->items as $item) {
                $item->product_name = ArabicPdf::reshape($item->product_name);
                $item->unit = ArabicPdf::reshape($item->unit);
            }
        }

        // Reshape expenses
        $cats = Expense::categories();
        foreach ($data['expenses'] as $item) {
            $item->category_label = ArabicPdf::reshape($cats[$item->category] ?? $item->category);
            if ($item->notes) {
                $item->notes = ArabicPdf::reshape($item->notes);
            }
        }

        // Reshape static labels
        $data['label_milk']         = ArabicPdf::reshape('جمع الحليب');
        $data['label_production']   = ArabicPdf::reshape('الإنتاج');
        $data['label_distribution'] = ArabicPdf::reshape('التوزيع');
        $data['label_expenses']     = ArabicPdf::reshape('المصروفات');
        $data['label_total']        = ArabicPdf::reshape('الإجمالي');
        $data['label_net_balance']  = ArabicPdf::reshape('الرصيد الصافي للفترة');
        $data['label_factory']      = ArabicPdf::reshape('مصنع الألبان');
        $data['label_farmer']       = ArabicPdf::reshape('المزارع');
        $data['label_driver']       = ArabicPdf::reshape('السائق');
        $data['label_vehicle']      = ArabicPdf::reshape('رقم السيارة');
        $data['label_qty']          = ArabicPdf::reshape('الكمية');
        $data['label_price']        = ArabicPdf::reshape('سعر الليتر');
        $data['label_subtotal']     = ArabicPdf::reshape('الإجمالي');
        $data['label_date']         = ArabicPdf::reshape('التاريخ');
        $data['label_product']      = ArabicPdf::reshape('اسم المنتج');
        $data['label_type']         = ArabicPdf::reshape('النوع');
        $data['label_unit']         = ArabicPdf::reshape('الوحدة');
        $data['label_shop']         = ArabicPdf::reshape('المحل');
        $data['label_products']     = ArabicPdf::reshape('المنتجات');
        $data['label_category']     = ArabicPdf::reshape('التصنيف');
        $data['label_amount']       = ArabicPdf::reshape('المبلغ');
        $data['label_notes']        = ArabicPdf::reshape('ملاحظات');
        $data['label_no_data']      = ArabicPdf::reshape('لا توجد بيانات في هذه الفترة');
        $data['label_total_sales']  = ArabicPdf::reshape('إجمالي المبيعات');
        $data['label_milk_total']   = ArabicPdf::reshape('إجمالي الحليب المجموع');
        $data['label_sales_total']  = ArabicPdf::reshape('إجمالي المبيعات (جنيه)');
        $data['label_exp_total']    = ArabicPdf::reshape('إجمالي المصروفات (جنيه)');
        $data['label_debts_total']  = ArabicPdf::reshape('الديون المتبقية (جنيه)');
        $data['label_net_formula']  = ArabicPdf::reshape('مبيعات + قيمة حليب - مصروفات');
        $data['label_footer']       = ArabicPdf::reshape('تم إنشاء هذا التقرير بواسطة نظام إدارة مصنع الألبان');
        $data['label_gineh']        = ArabicPdf::reshape('جنيه');
        $data['label_liter']        = ArabicPdf::reshape('لتر');
        $data['label_ops']          = ArabicPdf::reshape('عملية');
        $data['label_batch']        = ArabicPdf::reshape('دفعة');
        $data['label_item']         = ArabicPdf::reshape('بند');
        $data['label_print_date']   = ArabicPdf::reshape('تاريخ الطباعة');
        $data['label_from']         = ArabicPdf::reshape('من');
        $data['label_to_word']      = ArabicPdf::reshape('إلى');

        return $data;
    }

    private function getDateRange(Request $request): array
    {
        return match ($request->period) {
            'today'  => [Carbon::today(), Carbon::today()->endOfDay()],
            'week'   => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
            'month'  => [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()],
            'custom' => [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay(),
            ],
            default  => [Carbon::today(), Carbon::today()->endOfDay()],
        };
    }

    private function getPeriodLabel(string $period, Carbon $from, Carbon $to): string
    {
        return match ($period) {
            'today'  => 'اليوم - ' . $from->format('d/m/Y'),
            'week'   => 'آخر 7 أيام',
            'month'  => 'آخر 30 يوم',
            'custom' => 'من ' . $from->format('d/m/Y') . ' إلى ' . $to->format('d/m/Y'),
            default  => '',
        };
    }

    private function gatherReportData(Carbon $from, Carbon $to): array
    {
        // Milk Collection
        $milkCollections = MilkCollection::whereBetween('collection_date', [$from, $to])->get();
        $totalMilkLiters = $milkCollections->sum('quantity_liters');
        $totalMilkValue  = $milkCollections->sum('total_amount');

        // Production
        $productions      = Production::whereBetween('production_date', [$from->toDateString(), $to->toDateString()])->get();
        $productionByType = $productions->groupBy('product_type');

        // Distribution
        $distributions          = Distribution::with('items')->whereBetween('delivery_date', [$from, $to])->get();
        $totalDistributionValue = $distributions->sum('total_value');
        $totalDistributions     = $distributions->count();

        // Debts
        $totalOutstandingDebts = Debt::whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - paid_amount'));
        $newDebts         = Debt::whereBetween('debt_date', [$from->toDateString(), $to->toDateString()])->get();
        $newDebtValue     = $newDebts->sum('total_amount');
        $paymentsInPeriod = \App\Models\DebtPayment::whereBetween('payment_date', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');

        // Expenses
        $expenses           = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])->get();
        $totalExpenses      = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category');

        // Net Balance
        $netBalance = $totalMilkValue + $totalDistributionValue - $totalExpenses;

        return compact(
            'milkCollections', 'totalMilkLiters', 'totalMilkValue',
            'productions', 'productionByType',
            'distributions', 'totalDistributionValue', 'totalDistributions',
            'totalOutstandingDebts', 'newDebts', 'newDebtValue', 'paymentsInPeriod',
            'expenses', 'totalExpenses', 'expensesByCategory',
            'netBalance'
        );
    }
}
