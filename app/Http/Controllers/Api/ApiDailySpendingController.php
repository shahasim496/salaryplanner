<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailySpending;
use App\Models\SalaryPlan;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiDailySpendingController extends Controller
{
    /**
     * Get all daily spendings for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
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
                ->paginate($request->get('per_page', 20));

            // Calculate totals
            $totalSpending = (clone $query)->sum('amount');
            $spendingByCategory = (clone $query)
                ->select('category', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daily spendings retrieved successfully',
                'data' => [
                    'spendings' => $dailySpendings->items(),
                    'pagination' => [
                        'current_page' => $dailySpendings->currentPage(),
                        'last_page' => $dailySpendings->lastPage(),
                        'per_page' => $dailySpendings->perPage(),
                        'total' => $dailySpendings->total(),
                    ],
                    'total_spending' => $totalSpending,
                    'spending_by_category' => $spendingByCategory,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily spendings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new daily spending
     */
    public function store(Request $request): JsonResponse
    {
        try {
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
                return response()->json([
                    'success' => false,
                    'message' => 'Selected category does not exist in this plan',
                ], 422);
            }

            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            $dailySpending = DailySpending::create($validated);

            // Update expense actual_amount for this category
            $this->updateExpenseFromDailySpending($salaryPlan, $validated['category']);

            // Recalculate plan totals
            $this->recalculatePlanTotals($salaryPlan);

            return response()->json([
                'success' => true,
                'message' => 'Daily spending added successfully',
                'data' => ['spending' => $dailySpending],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create daily spending',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific daily spending
     */
    public function show(string $id): JsonResponse
    {
        try {
            $dailySpending = DailySpending::with('salaryPlan')
                ->whereHas('salaryPlan', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Daily spending retrieved successfully',
                'data' => ['spending' => $dailySpending],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily spending',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a daily spending
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
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
                return response()->json([
                    'success' => false,
                    'message' => 'Selected category does not exist in this plan',
                ], 422);
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

            return response()->json([
                'success' => true,
                'message' => 'Daily spending updated successfully',
                'data' => ['spending' => $dailySpending],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update daily spending',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a daily spending
     */
    public function destroy(string $id): JsonResponse
    {
        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Daily spending deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete daily spending',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update expense actual_amount based on daily spendings for a category
     */
    private function updateExpenseFromDailySpending(SalaryPlan $salaryPlan, string $category): void
    {
        $totalDailySpending = DailySpending::where('salary_plan_id', $salaryPlan->id)
            ->where('category', $category)
            ->sum('amount');

        $expenses = Expense::where('salary_plan_id', $salaryPlan->id)
            ->where('category', $category)
            ->get();

        if ($expenses->isEmpty()) {
            return;
        }

        if ($expenses->count() === 1) {
            $expense = $expenses->first();
            $expense->update([
                'actual_amount' => $totalDailySpending,
                'is_paid' => $totalDailySpending >= $expense->planned_amount,
                'paid_date' => $totalDailySpending >= $expense->planned_amount ? now() : null,
                'updated_by' => Auth::id(),
            ]);
        } else {
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
}

