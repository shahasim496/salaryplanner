<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="daily-spendings" subtitle="Daily spending" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Edit spending</p>
                        <h2>Edit Daily Spending</h2>
                        <span>Update spending details. Changes will automatically update the plan's expense category.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('daily-spendings.update', $dailySpending->id) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label>Salary Plan</label>
                        <input type="text" value="{{ $dailySpending->salaryPlan->month }} - PKR{{ number_format($dailySpending->salaryPlan->total_income, 2) }}" class="planner-input" disabled style="background:var(--bg-light);">
                        <small style="color:var(--muted); margin-top:4px; display:block;">Plan cannot be changed after creation.</small>
                    </div>
                    <div class="form-field">
                        <label for="category">Category <span style="color:#dc2626;">*</span></label>
                        <select id="category" name="category" class="planner-select" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ (old('category', $dailySpending->category) === $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="amount">Amount <span style="color:#dc2626;">*</span></label>
                        <input id="amount" type="number" name="amount" value="{{ old('amount', $dailySpending->amount) }}" class="planner-input" required step="0.01" min="0.01" placeholder="0.00">
                        <x-input-error :messages="$errors->get('amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="spending_date">Date <span style="color:#dc2626;">*</span></label>
                        <input id="spending_date" type="date" name="spending_date" value="{{ old('spending_date', $dailySpending->spending_date->format('Y-m-d')) }}" class="planner-input" required>
                        <x-input-error :messages="$errors->get('spending_date')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="planner-textarea" placeholder="Optional description for this spending.">{{ old('description', $dailySpending->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('daily-spendings.index') }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Update Spending</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>

