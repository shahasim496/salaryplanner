<?php

namespace App\Http\Controllers;

use App\Models\SalaryPlan;
use App\Models\Loan;
use App\Models\Investment;
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

        // Loans summary
        $loansOwedToMe = Loan::where('user_id', $userId)
            ->where('loan_type', 'owed_to_me')
            ->sum('remaining_amount');
        $loansIOwe = Loan::where('user_id', $userId)
            ->where('loan_type', 'owed_by_me')
            ->sum('remaining_amount');
        $totalLoansOwedToMe = Loan::where('user_id', $userId)
            ->where('loan_type', 'owed_to_me')
            ->sum('total_loaned');
        $totalLoansIOwe = Loan::where('user_id', $userId)
            ->where('loan_type', 'owed_by_me')
            ->sum('total_loaned');
        $recentLoans = Loan::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Investments summary
        $totalInvested = Investment::where('user_id', $userId)->sum('total_invested');
        $totalWithdrawn = Investment::where('user_id', $userId)->sum('total_withdrawn');
        $totalInvestmentProfit = Investment::where('user_id', $userId)->sum('total_profit');
        $totalInvestmentLoss = Investment::where('user_id', $userId)->sum('total_loss');
        $totalCurrentValue = Investment::where('user_id', $userId)->sum('current_value');
        $recentInvestments = Investment::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'currentPlan',
            'allPlans',
            'monthlyTrend',
            'expenseCategories',
            'savingsProgress',
            'totalIncome',
            'totalExpenses',
            'totalSavings',
            'totalRemaining',
            'loansOwedToMe',
            'loansIOwe',
            'totalLoansOwedToMe',
            'totalLoansIOwe',
            'recentLoans',
            'totalInvested',
            'totalWithdrawn',
            'totalInvestmentProfit',
            'totalInvestmentLoss',
            'totalCurrentValue',
            'recentInvestments'
        ));
    }
}
