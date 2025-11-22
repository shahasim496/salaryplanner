<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanEntry;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::where('user_id', Auth::id())
            ->with(['loanEntries', 'loanPayments'])
            ->orderBy('person_name')
            ->orderBy('loan_type')
            ->paginate(15);

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

        return view('loans.index', compact('loans', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('loans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_name' => 'required|string|max:100',
            'loan_type' => 'required|in:owed_to_me,owed_by_me',
            'notes' => 'nullable|string',
        ]);

        // Check if loan already exists for this person and type
        $existingLoan = Loan::where('user_id', Auth::id())
            ->where('person_name', $validated['person_name'])
            ->where('loan_type', $validated['loan_type'])
            ->first();

        if ($existingLoan) {
            return redirect()->back()
                ->withErrors(['person_name' => 'A loan record already exists for this person and type. Please edit the existing record instead.'])
                ->withInput();
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

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Loan record created. Now you can add loan entries and payments.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->with(['loanEntries' => function($query) {
                $query->orderBy('entry_date', 'desc');
            }, 'loanPayments' => function($query) {
                $query->orderBy('payment_date', 'desc');
            }])
            ->findOrFail($id);

        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('loans.edit', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($id);

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

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Loan record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($id);

        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan record deleted successfully.');
    }

    /**
     * Add a loan entry (individual loan amount)
     */
    public function addEntry(Request $request, string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'entry_date' => 'required|date',
        ]);

        LoanEntry::create([
            'loan_id' => $loan->id,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'entry_date' => $validated['entry_date'],
            'created_by' => Auth::id(),
        ]);

        $loan->recalculateTotals();

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Loan entry added successfully.');
    }

    /**
     * Add a payment
     */
    public function addPayment(Request $request, string $id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        LoanPayment::create([
            'loan_id' => $loan->id,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'payment_date' => $validated['payment_date'],
            'created_by' => Auth::id(),
        ]);

        $loan->recalculateTotals();

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Delete a loan entry
     */
    public function deleteEntry(string $loanId, string $entryId)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($loanId);

        $entry = LoanEntry::where('loan_id', $loan->id)
            ->findOrFail($entryId);

        $entry->delete();
        $loan->recalculateTotals();

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Loan entry deleted successfully.');
    }

    /**
     * Delete a payment
     */
    public function deletePayment(string $loanId, string $paymentId)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->findOrFail($loanId);

        $payment = LoanPayment::where('loan_id', $loan->id)
            ->findOrFail($paymentId);

        $payment->delete();
        $loan->recalculateTotals();

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Payment deleted successfully.');
    }
}
