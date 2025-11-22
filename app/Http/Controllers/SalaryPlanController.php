<?php

namespace App\Http\Controllers;

use App\Models\SalaryPlan;
use App\Models\SalaryItem;
use App\Models\Expense;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $baseQuery = SalaryPlan::where('user_id', Auth::id());

        $salaryPlans = (clone $baseQuery)
            ->orderBy('month', 'desc')
            ->paginate(12);

        $totals = [
            'income' => (clone $baseQuery)->sum('total_income'),
            'expenses' => (clone $baseQuery)->sum('total_expenses'),
            'savings' => (clone $baseQuery)->sum('total_savings'),
            'remaining' => (clone $baseQuery)->sum('remaining_amount'),
        ];

        $latestPlan = (clone $baseQuery)
            ->orderBy('month', 'desc')
            ->first();

        $quickCategories = $this->quickEntryCategories();

        return view('salary-plans.index', compact('salaryPlans', 'totals', 'latestPlan', 'quickCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $quickCategories = $this->quickEntryCategories();
        return view('salary-plans.create', compact('quickCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'notes' => 'nullable|string',
            'income_amount' => 'nullable|numeric|min:0',
            'savings_amount' => 'nullable|numeric|min:0',
            'expense_inputs' => 'sometimes|array',
            'expense_inputs.*' => 'nullable|numeric|min:0',
        ]);

        $planData = [
            'month' => $validated['month'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => 'Active',
        ];

        $salaryPlan = SalaryPlan::create($planData);

        $totalIncome = 0;
        $totalExpenses = 0;
        $totalSavings = 0;

        $incomeAmount = $validated['income_amount'] ?? null;
        if ($incomeAmount && $incomeAmount > 0) {
            SalaryItem::create([
                'salary_plan_id' => $salaryPlan->id,
                'item_name' => 'Monthly Salary',
                'item_type' => 'Income',
                'category' => 'Salary',
                'amount' => $incomeAmount,
                'description' => 'Quick entry salary',
                'is_recurring' => true,
                'priority' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            $totalIncome = $incomeAmount;
        }

        $expenseInputs = $validated['expense_inputs'] ?? [];
        foreach ($expenseInputs as $key => $amount) {
            if ($amount && $amount > 0) {
                $label = $this->quickEntryCategories()[$key] ?? ucfirst($key);
                Expense::create([
                    'salary_plan_id' => $salaryPlan->id,
                    'expense_name' => $label,
                    'category' => $label,
                    'planned_amount' => $amount,
                    'actual_amount' => null,
                    'description' => 'Quick entry expense',
                    'is_paid' => false,
                    'is_recurring' => true,
                    'priority' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $totalExpenses += $amount;
            }
        }

        $savingsAmount = $validated['savings_amount'] ?? null;
        if ($savingsAmount && $savingsAmount > 0) {
            Saving::create([
                'salary_plan_id' => $salaryPlan->id,
                'saving_name' => 'Savings Envelope',
                'saving_type' => 'Fixed',
                'planned_amount' => $savingsAmount,
                'actual_amount' => 0,
                'accumulated_amount' => 0,
                'description' => 'Quick entry savings',
                'target_goal' => null,
                'target_amount' => $savingsAmount,
                'target_date' => null,
                'priority' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            $totalSavings = $savingsAmount;
        }

        // Use recalculatePlanTotals to correctly calculate remaining (only deducts paid/completed items)
        // Since all items are new and not paid, remaining will equal income
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Salary plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salaryPlan = SalaryPlan::with(['salaryItems', 'expenses', 'savings'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Calculate totals
        $totalIncome = $salaryPlan->salaryItems()
            ->where('item_type', 'Income')
            ->sum('amount');

        $totalDeductions = $salaryPlan->salaryItems()
            ->where('item_type', 'Deduction')
            ->sum('amount');

        $netIncome = $totalIncome - $totalDeductions;

        $totalPlannedExpenses = $salaryPlan->expenses()->sum('planned_amount');
        $totalActualExpenses = $salaryPlan->expenses()->sum('actual_amount') ?? 0;
        $totalPlannedSavings = $salaryPlan->savings()->sum('planned_amount');
        $totalActualSavings = $salaryPlan->savings()->sum('actual_amount') ?? 0;

        // Calculate paid/completed expenses (only what's deducted from remaining)
        $paidExpenses = $salaryPlan->expenses()
            ->get()
            ->filter(function($expense) {
                return $expense->is_paid || ($expense->actual_amount && $expense->actual_amount >= $expense->planned_amount);
            })
            ->sum(function($expense) {
                return ($expense->actual_amount && $expense->actual_amount > 0) 
                    ? $expense->actual_amount 
                    : $expense->planned_amount;
            });

        // Calculate completed savings (only what's deducted from remaining)
        $completedSavings = $salaryPlan->savings()
            ->get()
            ->filter(function($saving) {
                return $saving->actual_amount && $saving->actual_amount >= $saving->planned_amount;
            })
            ->sum(function($saving) {
                return $saving->actual_amount;
            });

        // Recalculate totals using the method that only deducts paid/completed items
        $this->recalculatePlanTotals($salaryPlan);
        
        // Refresh to get updated values
        $salaryPlan->refresh();
        $remainingAmount = $salaryPlan->remaining_amount;

        // Chart data
        $expenseByCategory = $salaryPlan->expenses()
            ->select('category', DB::raw('SUM(planned_amount) as total'))
            ->groupBy('category')
            ->get();

        $savingsByType = $salaryPlan->savings()
            ->select('saving_type', DB::raw('SUM(planned_amount) as total'))
            ->groupBy('saving_type')
            ->get();

        return view('salary-plans.show', compact(
            'salaryPlan',
            'totalIncome',
            'totalDeductions',
            'netIncome',
            'totalPlannedExpenses',
            'totalActualExpenses',
            'totalPlannedSavings',
            'totalActualSavings',
            'paidExpenses',
            'completedSavings',
            'remainingAmount',
            'expenseByCategory',
            'savingsByType'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $salaryPlan = SalaryPlan::with(['salaryItems', 'expenses', 'savings'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Get current totals
        $currentIncome = $salaryPlan->salaryItems()
            ->where('item_type', 'Income')
            ->sum('amount');
        
        $currentSavings = $salaryPlan->savings()->sum('planned_amount');
        
        // Get expenses by category
        $currentExpenses = [];
        foreach ($salaryPlan->expenses as $expense) {
            $category = strtolower(str_replace(' ', '_', $expense->category));
            if (!isset($currentExpenses[$category])) {
                $currentExpenses[$category] = 0;
            }
            $currentExpenses[$category] += $expense->planned_amount;
        }

        $quickCategories = $this->quickEntryCategories();

        return view('salary-plans.edit', compact('salaryPlan', 'currentIncome', 'currentSavings', 'currentExpenses', 'quickCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salaryPlan = SalaryPlan::with(['salaryItems', 'expenses', 'savings'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'notes' => 'nullable|string',
            'status' => 'required|in:Active,Completed,Cancelled',
            'income_amount' => 'nullable|numeric|min:0',
            'savings_amount' => 'nullable|numeric|min:0',
            'expense_inputs' => 'nullable|array',
            'expense_inputs.*' => 'nullable|numeric|min:0',
        ]);

        $validated['updated_by'] = Auth::id();
        $salaryPlan->update([
            'month' => $validated['month'],
            'notes' => $validated['notes'],
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
        ]);

        // Update income
        if ($request->has('income_amount') && $request->input('income_amount') > 0) {
            $incomeItem = $salaryPlan->salaryItems()->where('item_type', 'Income')->first();
            if ($incomeItem) {
                $incomeItem->update([
                    'amount' => $request->input('income_amount'),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                SalaryItem::create([
                    'salary_plan_id' => $salaryPlan->id,
                    'item_name' => 'Monthly Salary',
                    'item_type' => 'Income',
                    'amount' => $request->input('income_amount'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        // Update expenses
        if ($request->has('expense_inputs')) {
            $expenseInputs = $request->input('expense_inputs', []);
            foreach ($expenseInputs as $key => $amount) {
                if ($amount && $amount > 0) {
                    $label = $this->quickEntryCategories()[$key] ?? ucfirst($key);
                    $expense = $salaryPlan->expenses()
                        ->where('category', $label)
                        ->first();
                    
                    if ($expense) {
                        $expense->update([
                            'planned_amount' => $amount,
                            'updated_by' => Auth::id(),
                        ]);
                    } else {
                        Expense::create([
                            'salary_plan_id' => $salaryPlan->id,
                            'expense_name' => $label,
                            'category' => $label,
                            'planned_amount' => $amount,
                            'actual_amount' => null,
                            'description' => 'Quick entry expense',
                            'is_paid' => false,
                            'is_recurring' => true,
                            'priority' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }
            }
        }

        // Update savings
        if ($request->has('savings_amount') && $request->input('savings_amount') > 0) {
            $saving = $salaryPlan->savings()->first();
            if ($saving) {
                $saving->update([
                    'planned_amount' => $request->input('savings_amount'),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                Saving::create([
                    'salary_plan_id' => $salaryPlan->id,
                    'saving_name' => 'Monthly Savings',
                    'saving_type' => 'Fixed',
                    'planned_amount' => $request->input('savings_amount'),
                    'actual_amount' => 0,
                    'accumulated_amount' => 0,
                    'description' => 'Quick entry savings',
                    'target_goal' => null,
                    'target_amount' => $request->input('savings_amount'),
                    'target_date' => null,
                    'priority' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        // Recalculate totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Salary plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($id);
        $salaryPlan->delete();

        return redirect()->route('salary-plans.index')
            ->with('success', 'Salary plan deleted successfully.');
    }

    /**
     * Add salary item
     */
    public function addSalaryItem(Request $request, string $id)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'item_type' => 'required|in:Income,Deduction',
            'category' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'priority' => 'integer|min:0',
        ]);

        $validated['salary_plan_id'] = $salaryPlan->id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        SalaryItem::create($validated);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Salary item added successfully.');
    }

    /**
     * Add expense
     */
    public function addExpense(Request $request, string $id)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'expense_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'planned_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'paid_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
            'is_recurring' => 'boolean',
            'priority' => 'integer|min:0',
        ]);

        $validated['salary_plan_id'] = $salaryPlan->id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Expense added successfully.');
    }

    /**
     * Add saving
     */
    public function addSaving(Request $request, string $id)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'saving_name' => 'required|string|max:100',
            'saving_type' => 'required|in:Fixed,Variable,Emergency,Investment',
            'planned_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'target_goal' => 'nullable|string|max:255',
            'target_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
            'priority' => 'integer|min:0',
        ]);

        $validated['salary_plan_id'] = $salaryPlan->id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Saving::create($validated);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Saving goal added successfully.');
    }

    /**
     * Delete salary item
     */
    public function deleteSalaryItem(string $planId, string $itemId)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
        $item = SalaryItem::where('salary_plan_id', $salaryPlan->id)->findOrFail($itemId);
        $item->delete();

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Salary item deleted successfully.');
    }

    /**
     * Delete expense
     */
    public function deleteExpense(string $planId, string $expenseId)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
        $expense = Expense::where('salary_plan_id', $salaryPlan->id)->findOrFail($expenseId);
        $expense->delete();

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Delete saving
     */
    public function deleteSaving(string $planId, string $savingId)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
        $saving = Saving::where('salary_plan_id', $salaryPlan->id)->findOrFail($savingId);
        $saving->delete();

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Saving goal deleted successfully.');
    }

    /**
     * Update expense
     */
    public function updateExpense(Request $request, string $planId, string $expenseId)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
        $expense = Expense::where('salary_plan_id', $salaryPlan->id)->findOrFail($expenseId);

        $validated = $request->validate([
            'expense_name' => 'sometimes|string|max:100',
            'category' => 'sometimes|string|max:50',
            'planned_amount' => 'sometimes|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'is_paid' => 'sometimes|boolean',
            'paid_date' => 'nullable|date',
        ]);

        $validated['updated_by'] = Auth::id();
        
        // If actual_amount is set and equals planned_amount, mark as paid
        if (isset($validated['actual_amount']) && $validated['actual_amount'] > 0) {
            if (!isset($validated['is_paid'])) {
                $validated['is_paid'] = ($validated['actual_amount'] >= $expense->planned_amount);
            }
            if ($validated['is_paid'] && !isset($validated['paid_date'])) {
                $validated['paid_date'] = now();
            }
        }

        $expense->update($validated);

        // Recalculate totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Update saving
     */
    public function updateSaving(Request $request, string $planId, string $savingId)
    {
        $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
        $saving = Saving::where('salary_plan_id', $salaryPlan->id)->findOrFail($savingId);

        $validated = $request->validate([
            'saving_name' => 'sometimes|string|max:100',
            'saving_type' => 'sometimes|in:Fixed,Variable,Emergency,Investment',
            'planned_amount' => 'sometimes|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'target_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
        ]);

        $validated['updated_by'] = Auth::id();
        
        // Update accumulated amount if actual_amount is provided
        if (isset($validated['actual_amount']) && $validated['actual_amount'] > 0) {
            $validated['accumulated_amount'] = ($saving->accumulated_amount ?? 0) + ($validated['actual_amount'] - ($saving->actual_amount ?? 0));
        }

        $saving->update($validated);

        // Recalculate totals
        $this->recalculatePlanTotals($salaryPlan);

        return redirect()->route('salary-plans.show', $salaryPlan->id)
            ->with('success', 'Saving goal updated successfully.');
    }

    /**
     * Recalculate plan totals
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
                // Only include if paid or completed
                return $expense->is_paid || ($expense->actual_amount && $expense->actual_amount >= $expense->planned_amount);
            })
            ->sum(function($expense) {
                // Use actual_amount if set and > 0, otherwise use planned_amount
                return ($expense->actual_amount && $expense->actual_amount > 0) 
                    ? $expense->actual_amount 
                    : $expense->planned_amount;
            });
        
        // Only deduct completed savings from remaining amount (actual >= planned)
        $deductedSavings = $salaryPlan->savings()
            ->get()
            ->filter(function($saving) {
                // Only include if completed (actual >= planned)
                return $saving->actual_amount && $saving->actual_amount >= $saving->planned_amount;
            })
            ->sum(function($saving) {
                // Use actual_amount
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

    /**
     * Default quick-entry categories.
     */
    protected function quickEntryCategories(): array
    {
        return [
            'bills' => 'Bills & Utilities',
            'groceries' => 'Groceries',
            'rent' => 'Rent / Mortgage',
            'transport' => 'Transport & Fuel',
            'outing' => 'Outings & Leisure',
            'other' => 'Other Needs',
        ];
    }
}
