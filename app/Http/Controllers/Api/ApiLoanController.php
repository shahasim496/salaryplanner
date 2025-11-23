<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanEntry;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiLoanController extends Controller
{
    /**
     * Get all loans for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $loans = Loan::where('user_id', Auth::id())
                ->with(['loanEntries', 'loanPayments'])
                ->orderBy('person_name')
                ->orderBy('loan_type')
                ->paginate($request->get('per_page', 15));

            $summary = [
                'owed_to_me_total' => Loan::where('user_id', Auth::id())
                    ->where('loan_type', 'owed_to_me')
                    ->sum('remaining_amount'),
                'owed_by_me_total' => Loan::where('user_id', Auth::id())
                    ->where('loan_type', 'owed_by_me')
                    ->sum('remaining_amount'),
                'owed_to_me_paid' => Loan::where('user_id', Auth::id())
                    ->where('loan_type', 'owed_to_me')
                    ->sum('total_paid'),
                'owed_by_me_paid' => Loan::where('user_id', Auth::id())
                    ->where('loan_type', 'owed_by_me')
                    ->sum('total_paid'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Loans retrieved successfully',
                'data' => [
                    'loans' => $loans->items(),
                    'pagination' => [
                        'current_page' => $loans->currentPage(),
                        'last_page' => $loans->lastPage(),
                        'per_page' => $loans->perPage(),
                        'total' => $loans->total(),
                    ],
                    'summary' => $summary,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve loans',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new loan
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'person_name' => 'required|string|max:100',
                'loan_type' => 'required|in:owed_to_me,owed_by_me',
                'notes' => 'nullable|string',
            ]);

            // Check if loan already exists
            $existingLoan = Loan::where('user_id', Auth::id())
                ->where('person_name', $validated['person_name'])
                ->where('loan_type', $validated['loan_type'])
                ->first();

            if ($existingLoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'A loan record already exists for this person and type',
                ], 422);
            }

            $loan = Loan::create([
                'user_id' => Auth::id(),
                'person_name' => $validated['person_name'],
                'loan_type' => $validated['loan_type'],
                'total_loaned' => 0,
                'total_paid' => 0,
                'remaining_amount' => 0,
                'status' => 'Active',
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loan record created successfully',
                'data' => ['loan' => $loan],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create loan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific loan
     */
    public function show(string $id): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())
                ->with(['loanEntries' => function($query) {
                    $query->orderBy('entry_date', 'desc');
                }, 'loanPayments' => function($query) {
                    $query->orderBy('payment_date', 'desc');
                }])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Loan retrieved successfully',
                'data' => ['loan' => $loan],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve loan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a loan
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'person_name' => 'required|string|max:100',
                'loan_type' => 'required|in:owed_to_me,owed_by_me',
                'status' => 'required|in:Active,Paid,Partial',
                'notes' => 'nullable|string',
            ]);

            $loan->update([
                'person_name' => $validated['person_name'],
                'loan_type' => $validated['loan_type'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loan updated successfully',
                'data' => ['loan' => $loan],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a loan
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($id);
            $loan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loan deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a loan entry
     */
    public function addEntry(Request $request, string $id): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'description' => 'nullable|string',
                'entry_date' => 'required|date',
            ]);

            $entry = LoanEntry::create([
                'loan_id' => $loan->id,
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'entry_date' => $validated['entry_date'],
                'created_by' => Auth::id(),
            ]);

            $loan->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Loan entry added successfully',
                'data' => ['entry' => $entry],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add loan entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add a payment
     */
    public function addPayment(Request $request, string $id): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'description' => 'nullable|string',
                'payment_date' => 'required|date',
            ]);

            $payment = LoanPayment::create([
                'loan_id' => $loan->id,
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'payment_date' => $validated['payment_date'],
                'created_by' => Auth::id(),
            ]);

            $loan->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => ['payment' => $payment],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a loan entry
     */
    public function deleteEntry(string $loanId, string $entryId): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($loanId);
            $entry = LoanEntry::where('loan_id', $loan->id)->findOrFail($entryId);
            $entry->delete();
            $loan->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Loan entry deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loan entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a payment
     */
    public function deletePayment(string $loanId, string $paymentId): JsonResponse
    {
        try {
            $loan = Loan::where('user_id', Auth::id())->findOrFail($loanId);
            $payment = LoanPayment::where('loan_id', $loan->id)->findOrFail($paymentId);
            $payment->delete();
            $loan->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

