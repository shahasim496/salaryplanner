<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalaryPlan;
use App\Models\SalaryItem;
use App\Models\Expense;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiSalaryPlanController extends Controller
{
    /**
     * Get all salary plans for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseQuery = SalaryPlan::where('user_id', Auth::id());

            // Pagination
            $perPage = $request->get('per_page', 12);
            $salaryPlans = (clone $baseQuery)
                ->orderBy('month', 'desc')
                ->paginate($perPage);

            $totals = [
                'income' => (clone $baseQuery)->sum('total_income'),
                'expenses' => (clone $baseQuery)->sum('total_expenses'),
                'savings' => (clone $baseQuery)->sum('total_savings'),
                'remaining' => (clone $baseQuery)->sum('remaining_amount'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Salary plans retrieved successfully',
                'data' => [
                    'plans' => $salaryPlans->items(),
                    'pagination' => [
                        'current_page' => $salaryPlans->currentPage(),
                        'last_page' => $salaryPlans->lastPage(),
                        'per_page' => $salaryPlans->perPage(),
                        'total' => $salaryPlans->total(),
                    ],
                    'totals' => $totals,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve salary plans',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new salary plan
     */
    public function store(Request $request): JsonResponse
    {
        try {
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

            // Add income
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

            // Add expenses
            $expenseInputs = $validated['expense_inputs'] ?? [];
            $quickCategories = $this->quickEntryCategories();
            foreach ($expenseInputs as $key => $amount) {
                if ($amount && $amount > 0) {
                    $label = $quickCategories[$key] ?? ucfirst($key);
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

            // Add savings
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

            $this->recalculatePlanTotals($salaryPlan);
            $salaryPlan->refresh();
            $salaryPlan->load(['salaryItems', 'expenses', 'savings']);

            return response()->json([
                'success' => true,
                'message' => 'Salary plan created successfully',
                'data' => ['plan' => $salaryPlan],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create salary plan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific salary plan
     */
    public function show(string $id): JsonResponse
    {
        try {
            $salaryPlan = SalaryPlan::with(['salaryItems', 'expenses', 'savings'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

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

            $expenseByCategory = $salaryPlan->expenses()
                ->select('category', DB::raw('SUM(planned_amount) as total'))
                ->groupBy('category')
                ->get();

            $savingsByType = $salaryPlan->savings()
                ->select('saving_type', DB::raw('SUM(planned_amount) as total'))
                ->groupBy('saving_type')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Salary plan retrieved successfully',
                'data' => [
                    'plan' => $salaryPlan,
                    'summary' => [
                        'total_income' => $totalIncome,
                        'total_deductions' => $totalDeductions,
                        'net_income' => $netIncome,
                        'total_planned_expenses' => $totalPlannedExpenses,
                        'total_actual_expenses' => $totalActualExpenses,
                        'total_planned_savings' => $totalPlannedSavings,
                        'total_actual_savings' => $totalActualSavings,
                        'remaining_amount' => $salaryPlan->remaining_amount,
                    ],
                    'expense_by_category' => $expenseByCategory,
                    'savings_by_type' => $savingsByType,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve salary plan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a salary plan
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
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
                $quickCategories = $this->quickEntryCategories();
                foreach ($expenseInputs as $key => $amount) {
                    if ($amount && $amount > 0) {
                        $label = $quickCategories[$key] ?? ucfirst($key);
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

            $this->recalculatePlanTotals($salaryPlan);
            $salaryPlan->refresh();
            $salaryPlan->load(['salaryItems', 'expenses', 'savings']);

            return response()->json([
                'success' => true,
                'message' => 'Salary plan updated successfully',
                'data' => ['plan' => $salaryPlan],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update salary plan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a salary plan
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($id);
            $salaryPlan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Salary plan deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete salary plan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add salary item
     */
    public function addSalaryItem(Request $request, string $id): JsonResponse
    {
        try {
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

            $salaryItem = SalaryItem::create($validated);
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Salary item added successfully',
                'data' => ['item' => $salaryItem],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add salary item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add expense
     */
    public function addExpense(Request $request, string $id): JsonResponse
    {
        try {
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

            $expense = Expense::create($validated);
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Expense added successfully',
                'data' => ['expense' => $expense],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add expense',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add saving
     */
    public function addSaving(Request $request, string $id): JsonResponse
    {
        try {
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

            $saving = Saving::create($validated);
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Saving goal added successfully',
                'data' => ['saving' => $saving],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add saving',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update expense
     */
    public function updateExpense(Request $request, string $planId, string $expenseId): JsonResponse
    {
        try {
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
            
            if (isset($validated['actual_amount']) && $validated['actual_amount'] > 0) {
                if (!isset($validated['is_paid'])) {
                    $validated['is_paid'] = ($validated['actual_amount'] >= $expense->planned_amount);
                }
                if ($validated['is_paid'] && !isset($validated['paid_date'])) {
                    $validated['paid_date'] = now();
                }
            }

            $expense->update($validated);
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully',
                'data' => ['expense' => $expense],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update saving
     */
    public function updateSaving(Request $request, string $planId, string $savingId): JsonResponse
    {
        try {
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
            
            if (isset($validated['actual_amount']) && $validated['actual_amount'] > 0) {
                $validated['accumulated_amount'] = ($saving->accumulated_amount ?? 0) + ($validated['actual_amount'] - ($saving->actual_amount ?? 0));
            }

            $saving->update($validated);
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Saving goal updated successfully',
                'data' => ['saving' => $saving],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update saving',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete salary item
     */
    public function deleteSalaryItem(string $planId, string $itemId): JsonResponse
    {
        try {
            $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
            $item = SalaryItem::where('salary_plan_id', $salaryPlan->id)->findOrFail($itemId);
            $item->delete();
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Salary item deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete salary item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete expense
     */
    public function deleteExpense(string $planId, string $expenseId): JsonResponse
    {
        try {
            $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
            $expense = Expense::where('salary_plan_id', $salaryPlan->id)->findOrFail($expenseId);
            $expense->delete();
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete saving
     */
    public function deleteSaving(string $planId, string $savingId): JsonResponse
    {
        try {
            $salaryPlan = SalaryPlan::where('user_id', Auth::id())->findOrFail($planId);
            $saving = Saving::where('salary_plan_id', $salaryPlan->id)->findOrFail($savingId);
            $saving->delete();
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Saving goal deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete saving',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Recalculate plan totals
     */
    private function recalculatePlanTotals(SalaryPlan $salaryPlan): void
    {
        $totalIncome = $salaryPlan->salaryItems()->where('item_type', 'Income')->sum('amount');
        $totalDeductions = $salaryPlan->salaryItems()->where('item_type', 'Deduction')->sum('amount');
        $netIncome = $totalIncome - $totalDeductions;
        
        $totalPlannedExpenses = $salaryPlan->expenses()->sum('planned_amount');
        $totalPlannedSavings = $salaryPlan->savings()->sum('planned_amount');
        
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
        
        $deductedSavings = $salaryPlan->savings()
            ->get()
            ->filter(function($saving) {
                return $saving->actual_amount && $saving->actual_amount >= $saving->planned_amount;
            })
            ->sum(function($saving) {
                return $saving->actual_amount;
            });
        
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
     * Default quick-entry categories
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

