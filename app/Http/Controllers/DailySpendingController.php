<?php

namespace App\Http\Controllers;

use App\Models\DailySpending;
use App\Models\SalaryPlan;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailySpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailySpending::with('salaryPlan')
            ->whereHas('salaryPlan', function($q) {
                $q->where('user_id', Auth::id());
            });

        // Filter by plan if provided
        if ($request->has('plan_id') && $request->plan_id) {
            $query->where('salary_plan_id', $request->plan_id);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('spending_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('spending_date', '<=', $request->date_to);
        }

        $dailySpendings = $query->orderBy('spending_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get all plans for filter dropdown
        $plans = SalaryPlan::where('user_id', Auth::id())
            ->orderBy('month', 'desc')
            ->get();

        // Calculate totals
        $totalSpending = (clone $query)->sum('amount');
        $spendingByCategory = (clone $query)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('daily-spendings.index', compact('dailySpendings', 'plans', 'totalSpending', 'spendingByCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get all plans for the user
        $plans = SalaryPlan::where('user_id', Auth::id())
            ->orderBy('month', 'desc')
            ->get();

        // Get selected plan or latest plan
        $selectedPlanId = $request->get('plan_id');
        $selectedPlan = null;
        $categories = [];

        if ($selectedPlanId) {
            $selectedPlan = SalaryPlan::where('user_id', Auth::id())
                ->findOrFail($selectedPlanId);
            
            // Get unique categories from plan expenses
            $categories = Expense::where('salary_plan_id', $selectedPlanId)
                ->distinct()
                ->pluck('category')
                ->toArray();
        } elseif ($plans->count() > 0) {
            $selectedPlan = $plans->first();
            $categories = Expense::where('salary_plan_id', $selectedPlan->id)
                ->distinct()
                ->pluck('category')
                ->toArray();
        }

        return view('daily-spendings.create', compact('plans', 'selectedPlan', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'salary_plan_id' => 'required|exists:salary_plans,id',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'spending_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Verify plan belongs to user
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())
            ->findOrFail($validated['salary_plan_id']);

        // Verify category exists in plan expenses
        $categoryExists = Expense::where('salary_plan_id', $validated['salary_plan_id'])
            ->where('category', $validated['category'])
            ->exists();

        if (!$categoryExists) {
            return redirect()->back()
                ->withErrors(['category' => 'Selected category does not exist in this plan.'])
                ->withInput();
        }

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $dailySpending = DailySpending::create($validated);

        // Update expense actual_amount for this category
        $this->updateExpenseFromDailySpending($salaryPlan, $validated['category']);

        // Recalculate plan totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('daily-spendings.index', ['plan_id' => $salaryPlan->id])
            ->with('success', 'Daily spending added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dailySpending = DailySpending::with('salaryPlan')
            ->whereHas('salaryPlan', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $salaryPlan = $dailySpending->salaryPlan;
        
        // Get categories from plan expenses
        $categories = Expense::where('salary_plan_id', $salaryPlan->id)
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('daily-spendings.edit', compact('dailySpending', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dailySpending = DailySpending::with('salaryPlan')
            ->whereHas('salaryPlan', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $oldCategory = $dailySpending->category;
        $salaryPlan = $dailySpending->salaryPlan;

        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'spending_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Verify category exists in plan expenses
        $categoryExists = Expense::where('salary_plan_id', $salaryPlan->id)
            ->where('category', $validated['category'])
            ->exists();

        if (!$categoryExists) {
            return redirect()->back()
                ->withErrors(['category' => 'Selected category does not exist in this plan.'])
                ->withInput();
        }

        $validated['updated_by'] = Auth::id();

        $dailySpending->update($validated);

        // Update expense actual_amount for old and new categories
        if ($oldCategory !== $validated['category']) {
            $this->updateExpenseFromDailySpending($salaryPlan, $oldCategory);
        }
        $this->updateExpenseFromDailySpending($salaryPlan, $validated['category']);

        // Recalculate plan totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('daily-spendings.index', ['plan_id' => $salaryPlan->id])
            ->with('success', 'Daily spending updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dailySpending = DailySpending::with('salaryPlan')
            ->whereHas('salaryPlan', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $salaryPlan = $dailySpending->salaryPlan;
        $category = $dailySpending->category;

        $dailySpending->delete();

        // Update expense actual_amount for this category
        $this->updateExpenseFromDailySpending($salaryPlan, $category);

        // Recalculate plan totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('daily-spendings.index', ['plan_id' => $salaryPlan->id])
            ->with('success', 'Daily spending deleted successfully.');
    }

    /**
     * Update expense actual_amount based on daily spendings for a category
     */
    private function updateExpenseFromDailySpending(SalaryPlan $salaryPlan, string $category): void
    {
        // Get total daily spending for this category
        $totalDailySpending = DailySpending::where('salary_plan_id', $salaryPlan->id)
            ->where('category', $category)
            ->sum('amount');

        // Get expenses with this category
        $expenses = Expense::where('salary_plan_id', $salaryPlan->id)
            ->where('category', $category)
            ->get();

        if ($expenses->isEmpty()) {
            return;
        }

        // If there's only one expense, update it with total daily spending
        if ($expenses->count() === 1) {
            $expense = $expenses->first();
            $expense->update([
                'actual_amount' => $totalDailySpending,
                'is_paid' => $totalDailySpending >= $expense->planned_amount,
                'paid_date' => $totalDailySpending >= $expense->planned_amount ? now() : null,
                'updated_by' => Auth::id(),
            ]);
        } else {
            // For multiple expenses with same category, update the first expense with total
            // This represents the total spending for this category across all expenses
            $firstExpense = $expenses->first();
            $firstExpense->update([
                'actual_amount' => $totalDailySpending,
                'is_paid' => $totalDailySpending >= $firstExpense->planned_amount,
                'paid_date' => $totalDailySpending >= $firstExpense->planned_amount ? now() : null,
                'updated_by' => Auth::id(),
            ]);
        }
    }

    /**
     * Recalculate plan totals (similar to SalaryPlanController)
     */
    private function recalculatePlanTotals(SalaryPlan $salaryPlan): void
    {
        $totalIncome = $salaryPlan->salaryItems()->where('item_type', 'Income')->sum('amount');
        $totalDeductions = $salaryPlan->salaryItems()->where('item_type', 'Deduction')->sum('amount');
        $netIncome = $totalIncome - $totalDeductions;
        
        // Total planned amounts (for display)
        $totalPlannedExpenses = $salaryPlan->expenses()->sum('planned_amount');
        $totalPlannedSavings = $salaryPlan->savings()->sum('planned_amount');
        
        // Only deduct paid/completed expenses from remaining amount
        $deductedExpenses = $salaryPlan->expenses()
            ->get()
            ->filter(function($expense) {
                return $expense->is_paid || ($expense->actual_amount && $expense->actual_amount >= $expense->planned_amount);
            })
            ->sum(function($expense) {
                return ($expense->actual_amount && $expense->actual_amount > 0) 
                    ? $expense->actual_amount 
                    : $expense->planned_amount;
            });
        
        // Only deduct completed savings from remaining amount (actual >= planned)
        $deductedSavings = $salaryPlan->savings()
            ->get()
            ->filter(function($saving) {
                return $saving->actual_amount && $saving->actual_amount >= $saving->planned_amount;
            })
            ->sum(function($saving) {
                return $saving->actual_amount;
            });
        
        // Remaining amount only deducts paid/completed items
        $remainingAmount = $netIncome - $deductedExpenses - $deductedSavings;

        $salaryPlan->update([
            'total_income' => $netIncome,
            'total_expenses' => $totalPlannedExpenses,
            'total_savings' => $totalPlannedSavings,
            'remaining_amount' => $remainingAmount,
            'updated_by' => Auth::id(),
        ]);
    }
}
