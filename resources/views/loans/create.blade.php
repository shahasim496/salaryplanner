<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="loans" subtitle="Loans management" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">New person</p>
                        <h2>Add person for loan tracking</h2>
                        <span>Create a record for a person. You can add multiple loan entries and payments later.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('loans.store') }}" class="form-grid">
                    @csrf
                    <div class="form-field">
                        <label for="person_name">Person Name</label>
                        <input id="person_name" type="text" name="person_name" value="{{ old('person_name') }}" class="planner-input" required autofocus placeholder="Enter person's name">
                        <x-input-error :messages="$errors->get('person_name')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="loan_type">Loan Type</label>
                        <select id="loan_type" name="loan_type" class="planner-select" required>
                            <option value="">Select type</option>
                            <option value="owed_to_me" {{ old('loan_type') === 'owed_to_me' ? 'selected' : '' }}>Owed to Me (Someone owes me)</option>
                            <option value="owed_by_me" {{ old('loan_type') === 'owed_by_me' ? 'selected' : '' }}>I Owe (I owe someone)</option>
                        </select>
                        <x-input-error :messages="$errors->get('loan_type')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="planner-textarea" placeholder="Additional notes about this person.">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('loans.index') }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Create Record</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>
