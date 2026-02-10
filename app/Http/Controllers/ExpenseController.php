<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest('expense_date')->paginate(15);
        $categories = Expense::categories();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = Expense::categories();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|string',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string',
        ], [
            'amount.required'       => 'المبلغ مطلوب',
            'category.required'     => 'التصنيف مطلوب',
            'expense_date.required' => 'تاريخ المصروف مطلوب',
        ]);

        $validated['user_id'] = auth()->id();
        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'تم تسجيل المصروف بنجاح');
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::categories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|string',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }
}
