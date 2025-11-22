<?php

namespace App\Http\Controllers;

use App\Models\SalaryPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get current month plan
        $currentMonth = date('Y-m');
        $currentPlan = SalaryPlan::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->with(['salaryItems', 'expenses', 'savings'])
            ->first();

        // Get all plans for charts
        $allPlans = SalaryPlan::where('user_id', $userId)
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Monthly income/expense trend
        $monthlyTrend = SalaryPlan::where('user_id', $userId)
            ->select('month', 'total_income', 'total_expenses', 'total_savings')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Category-wise expense breakdown (last 3 months)
        // First get the last 3 months
        $lastThreeMonths = SalaryPlan::where('user_id', $userId)
            ->orderBy('month', 'desc')
            ->limit(3)
            ->pluck('month')
            ->toArray();

        $expenseCategories = DB::table('expenses')
            ->join('salary_plans', 'expenses.salary_plan_id', '=', 'salary_plans.id')
            ->where('salary_plans.user_id', $userId)
            ->whereIn('salary_plans.month', $lastThreeMonths)
            ->select('expenses.category', DB::raw('SUM(expenses.planned_amount) as total'))
            ->groupBy('expenses.category')
            ->get();

        // Savings progress
        $savingsProgress = DB::table('savings')
            ->join('salary_plans', 'savings.salary_plan_id', '=', 'salary_plans.id')
            ->where('salary_plans.user_id', $userId)
            ->select('savings.saving_type', DB::raw('SUM(savings.planned_amount) as planned'), DB::raw('SUM(savings.actual_amount) as actual'))
            ->groupBy('savings.saving_type')
            ->get();

        // Total statistics
        $totalIncome = SalaryPlan::where('user_id', $userId)->sum('total_income');
        $totalExpenses = SalaryPlan::where('user_id', $userId)->sum('total_expenses');
        $totalSavings = SalaryPlan::where('user_id', $userId)->sum('total_savings');
        $totalRemaining = SalaryPlan::where('user_id', $userId)->sum('remaining_amount');

        return view('dashboard', compact(
            'currentPlan',
            'allPlans',
            'monthlyTrend',
            'expenseCategories',
            'savingsProgress',
            'totalIncome',
            'totalExpenses',
            'totalSavings',
            'totalRemaining'
        ));
    }
}
