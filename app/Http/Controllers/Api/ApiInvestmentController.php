<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\InvestmentEntry;
use App\Models\InvestmentProfit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiInvestmentController extends Controller
{
    /**
     * Get all investments for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $investments = Investment::where('user_id', Auth::id())
                ->with(['investmentEntries', 'investmentProfits'])
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            $summary = [
                'total_invested' => Investment::where('user_id', Auth::id())->sum('total_invested'),
                'total_withdrawn' => Investment::where('user_id', Auth::id())->sum('total_withdrawn'),
                'total_profit' => Investment::where('user_id', Auth::id())->sum('total_profit'),
                'total_loss' => Investment::where('user_id', Auth::id())->sum('total_loss'),
                'current_value' => Investment::where('user_id', Auth::id())->sum('current_value'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Investments retrieved successfully',
                'data' => [
                    'investments' => $investments->items(),
                    'pagination' => [
                        'current_page' => $investments->currentPage(),
                        'last_page' => $investments->lastPage(),
                        'per_page' => $investments->perPage(),
                        'total' => $investments->total(),
                    ],
                    'summary' => $summary,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve investments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new investment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'investment_name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $investment = Investment::create([
                'user_id' => Auth::id(),
                'investment_name' => $validated['investment_name'],
                'description' => $validated['description'],
                'total_invested' => 0,
                'total_withdrawn' => 0,
                'total_profit' => 0,
                'total_loss' => 0,
                'current_value' => 0,
                'remaining_amount' => 0,
                'status' => 'Active',
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Investment created successfully',
                'data' => ['investment' => $investment],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific investment
     */
    public function show(string $id): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())
                ->with(['investmentEntries' => function($query) {
                    $query->orderBy('entry_date', 'desc');
                }, 'investmentProfits' => function($query) {
                    $query->orderBy('month', 'desc');
                }])
                ->findOrFail($id);

            $investment->recalculateTotals();
            $investment->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Investment retrieved successfully',
                'data' => ['investment' => $investment],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve investment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an investment
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'investment_name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'status' => 'required|in:Active,Closed',
                'notes' => 'nullable|string',
            ]);

            $investment->update([
                'investment_name' => $validated['investment_name'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Investment updated successfully',
                'data' => ['investment' => $investment],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update investment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an investment
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($id);
            $investment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Investment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete investment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add an investment entry
     */
    public function addEntry(Request $request, string $id): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'entry_type' => 'required|in:increase,decrease',
                'amount' => 'required|numeric|min:0.01',
                'description' => 'nullable|string',
                'entry_date' => 'required|date',
            ]);

            $entry = InvestmentEntry::create([
                'investment_id' => $investment->id,
                'entry_type' => $validated['entry_type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'entry_date' => $validated['entry_date'],
                'created_by' => Auth::id(),
            ]);

            $investment->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Investment entry added successfully',
                'data' => ['entry' => $entry],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add investment entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a profit/loss record
     */
    public function addProfit(Request $request, string $id): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'month' => 'required|date_format:Y-m',
                'profit_amount' => 'nullable|numeric|min:0',
                'loss_amount' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
            ]);

            $existing = InvestmentProfit::where('investment_id', $investment->id)
                ->where('month', $validated['month'])
                ->first();

            if ($existing) {
                $existing->update([
                    'profit_amount' => $validated['profit_amount'] ?? 0,
                    'loss_amount' => $validated['loss_amount'] ?? 0,
                    'description' => $validated['description'],
                ]);
                $profit = $existing;
            } else {
                $profit = InvestmentProfit::create([
                    'investment_id' => $investment->id,
                    'month' => $validated['month'],
                    'profit_amount' => $validated['profit_amount'] ?? 0,
                    'loss_amount' => $validated['loss_amount'] ?? 0,
                    'description' => $validated['description'],
                    'created_by' => Auth::id(),
                ]);
            }

            $investment->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Profit/loss record saved successfully',
                'data' => ['profit' => $profit],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add profit/loss record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an investment entry
     */
    public function deleteEntry(string $investmentId, string $entryId): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($investmentId);
            $entry = InvestmentEntry::where('investment_id', $investment->id)->findOrFail($entryId);
            $entry->delete();
            $investment->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Investment entry deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete investment entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a profit record
     */
    public function deleteProfit(string $investmentId, string $profitId): JsonResponse
    {
        try {
            $investment = Investment::where('user_id', Auth::id())->findOrFail($investmentId);
            $profit = InvestmentProfit::where('investment_id', $investment->id)->findOrFail($profitId);
            $profit->delete();
            $investment->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Profit/loss record deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profit/loss record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

