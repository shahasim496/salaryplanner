<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentEntry;
use App\Models\InvestmentProfit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $investments = Investment::where('user_id', Auth::id())
            ->with(['investmentEntries', 'investmentProfits'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $summary = [
            'total_invested' => Investment::where('user_id', Auth::id())->sum('total_invested'),
            'total_withdrawn' => Investment::where('user_id', Auth::id())->sum('total_withdrawn'),
            'total_profit' => Investment::where('user_id', Auth::id())->sum('total_profit'),
            'total_loss' => Investment::where('user_id', Auth::id())->sum('total_loss'),
            'current_value' => Investment::where('user_id', Auth::id())->sum('current_value'),
        ];

        return view('investments.index', compact('investments', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('investments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Investment created. Now you can add investment entries and profit/loss records.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->with(['investmentEntries' => function($query) {
                $query->orderBy('entry_date', 'desc');
            }, 'investmentProfits' => function($query) {
                $query->orderBy('month', 'desc');
            }])
            ->findOrFail($id);

        // Recalculate totals to ensure all fields are up to date
        $investment->recalculateTotals();
        $investment->refresh();

        return view('investments.show', compact('investment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('investments.edit', compact('investment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($id);

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

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Investment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($id);

        $investment->delete();

        return redirect()->route('investments.index')
            ->with('success', 'Investment deleted successfully.');
    }

    /**
     * Add an investment entry (increase or decrease)
     */
    public function addEntry(Request $request, string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'entry_type' => 'required|in:increase,decrease',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'entry_date' => 'required|date',
        ]);

        InvestmentEntry::create([
            'investment_id' => $investment->id,
            'entry_type' => $validated['entry_type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'entry_date' => $validated['entry_date'],
            'created_by' => Auth::id(),
        ]);

        $investment->recalculateTotals();

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Investment entry added successfully.');
    }

    /**
     * Add a profit/loss record for a month
     */
    public function addProfit(Request $request, string $id)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'profit_amount' => 'nullable|numeric|min:0',
            'loss_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Check if profit record already exists for this month
        $existing = InvestmentProfit::where('investment_id', $investment->id)
            ->where('month', $validated['month'])
            ->first();

        if ($existing) {
            $existing->update([
                'profit_amount' => $validated['profit_amount'] ?? 0,
                'loss_amount' => $validated['loss_amount'] ?? 0,
                'description' => $validated['description'],
            ]);
        } else {
            InvestmentProfit::create([
                'investment_id' => $investment->id,
                'month' => $validated['month'],
                'profit_amount' => $validated['profit_amount'] ?? 0,
                'loss_amount' => $validated['loss_amount'] ?? 0,
                'description' => $validated['description'],
                'created_by' => Auth::id(),
            ]);
        }

        $investment->recalculateTotals();

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Profit/loss record saved successfully.');
    }

    /**
     * Delete an investment entry
     */
    public function deleteEntry(string $investmentId, string $entryId)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($investmentId);

        $entry = InvestmentEntry::where('investment_id', $investment->id)
            ->findOrFail($entryId);

        $entry->delete();
        $investment->recalculateTotals();

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Investment entry deleted successfully.');
    }

    /**
     * Delete a profit record
     */
    public function deleteProfit(string $investmentId, string $profitId)
    {
        $investment = Investment::where('user_id', Auth::id())
            ->findOrFail($investmentId);

        $profit = InvestmentProfit::where('investment_id', $investment->id)
            ->findOrFail($profitId);

        $profit->delete();
        $investment->recalculateTotals();

        return redirect()->route('investments.show', $investment->id)
            ->with('success', 'Profit/loss record deleted successfully.');
    }
}
