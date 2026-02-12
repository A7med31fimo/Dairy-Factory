<?php

namespace App\Http\Controllers;

use App\Models\MilkCollection;
use App\Models\Production;
use App\Models\Distribution;
use App\Models\Debt;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mpdf\Mpdf;
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

        // Render the view to HTML
        $html = view('reports.pdf', $data)->render();

        // Create mPDF instance with RTL support
        $mpdf = new Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'default_font'      => 'dejavusans',
            'margin_left'       => 10,
            'margin_right'      => 10,
            'margin_top'        => 10,
            'margin_bottom'     => 10,
            'margin_header'     => 5,
            'margin_footer'     => 5,
            'orientation'       => 'P',
            'directionality'    => 'rtl',  // RTL support!
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output as download
        $filename = 'report_' . $dateFrom->format('Y-m-d') . '_' . $dateTo->format('Y-m-d') . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
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
