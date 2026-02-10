<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with('payments')->latest('debt_date')->paginate(15);
        $totalUnpaid = Debt::whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - paid_amount'));
        return view('debts.index', compact('debts', 'totalUnpaid'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'debtor_name'  => 'required|string|max:255',
            'reason'       => 'required|string',
            'total_amount' => 'required|numeric|min:0.01',
            'debt_date'    => 'required|date',
            'notes'        => 'nullable|string',
        ], [
            'debtor_name.required'  => 'اسم المدين مطلوب',
            'reason.required'       => 'سبب الدين مطلوب',
            'total_amount.required' => 'المبلغ مطلوب',
            'debt_date.required'    => 'تاريخ الدين مطلوب',
        ]);

        $validated['user_id']     = auth()->id();
        $validated['paid_amount'] = 0;
        $validated['status']      = 'unpaid';

        Debt::create($validated);

        return redirect()->route('debts.index')
            ->with('success', 'تم تسجيل الدين بنجاح');
    }

    public function show(Debt $debt)
    {
        $debt->load('payments');
        return view('debts.show', compact('debt'));
    }

    public function edit(Debt $debt)
    {
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'debtor_name'  => 'required|string|max:255',
            'reason'       => 'required|string',
            'total_amount' => 'required|numeric|min:0.01',
            'debt_date'    => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $debt->update($validated);
        $debt->updateStatus();

        return redirect()->route('debts.index')
            ->with('success', 'تم تحديث سجل الدين بنجاح');
    }

    public function destroy(Debt $debt)
    {
        $debt->delete();
        return redirect()->route('debts.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }

    public function addPayment(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'amount'       => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
        ], [
            'amount.required'       => 'مبلغ الدفعة مطلوب',
            'payment_date.required' => 'تاريخ الدفع مطلوب',
        ]);

        $remaining = $debt->total_amount - $debt->paid_amount;
        if ($validated['amount'] > $remaining) {
            return back()->withErrors(['amount' => 'المبلغ أكبر من المتبقي: ' . number_format($remaining, 2)]);
        }

        DB::transaction(function () use ($validated, $debt) {
            DebtPayment::create(array_merge($validated, [
                'debt_id' => $debt->id,
                'user_id' => auth()->id(),
            ]));
            $debt->paid_amount += $validated['amount'];
            $debt->updateStatus();
        });

        return redirect()->route('debts.show', $debt)
            ->with('success', 'تم تسجيل الدفعة بنجاح');
    }
}
