<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="loans" subtitle="Loans management" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Edit person</p>
                        <h2>Update person details</h2>
                        <span>Modify person name, loan type, or notes.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('loans.update', $loan->id) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label for="person_name">Person Name</label>
                        <input id="person_name" type="text" name="person_name" value="{{ old('person_name', $loan->person_name) }}" class="planner-input" required autofocus>
                        <x-input-error :messages="$errors->get('person_name')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="loan_type">Loan Type</label>
                        <select id="loan_type" name="loan_type" class="planner-select" required>
                            <option value="owed_to_me" {{ old('loan_type', $loan->loan_type) === 'owed_to_me' ? 'selected' : '' }}>Owed to Me (Someone owes me)</option>
                            <option value="owed_by_me" {{ old('loan_type', $loan->loan_type) === 'owed_by_me' ? 'selected' : '' }}>I Owe (I owe someone)</option>
                        </select>
                        <x-input-error :messages="$errors->get('loan_type')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="planner-select" required>
                            <option value="Active" {{ old('status', $loan->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Partial" {{ old('status', $loan->status) === 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Paid" {{ old('status', $loan->status) === 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="planner-textarea">{{ old('notes', $loan->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="planner-summary">
                        <div class="summary-chip">
                            <span>Total Loaned</span>
                            <strong>PKR{{ number_format($loan->total_loaned, 2) }}</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Total Paid</span>
                            <strong>PKR{{ number_format($loan->total_paid, 2) }}</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Remaining</span>
                            <strong>PKR{{ number_format($loan->remaining_amount, 2) }}</strong>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('loans.show', $loan->id) }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Update Details</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>
