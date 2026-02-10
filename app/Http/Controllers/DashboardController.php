<?php

namespace App\Http\Controllers;

use App\Models\MilkCollection;
use App\Models\Distribution;
use App\Models\Debt;
use App\Models\Expense;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Today's stats
        $todayMilk = MilkCollection::whereDate('collection_date', $today)->sum('quantity_liters');
        $todayMilkValue = MilkCollection::whereDate('collection_date', $today)->sum('total_amount');
        $todayDistribution = Distribution::whereDate('delivery_date', $today)->sum('total_value');
        $todayExpenses = Expense::where('expense_date', $today)->sum('amount');

        // Total outstanding debts
        $totalDebts = Debt::whereIn('status', ['unpaid', 'partial'])->sum(\DB::raw('total_amount - paid_amount'));

        // Recent records
        $recentMilk = MilkCollection::latest('collection_date')->take(5)->get();
        $recentDistributions = Distribution::with('items')->latest('delivery_date')->take(5)->get();
        $recentExpenses = Expense::latest('expense_date')->take(5)->get();

        return view('dashboard.index', compact(
            'todayMilk', 'todayMilkValue', 'todayDistribution',
            'todayExpenses', 'totalDebts',
            'recentMilk', 'recentDistributions', 'recentExpenses'
        ));
    }
}
