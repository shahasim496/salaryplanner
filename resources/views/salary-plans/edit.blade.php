<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="" subtitle="Planning studio" />

        <main class="planner-main">
          

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Plan inputs</p>
                        <h2>Edit salary plan</h2>
                        <span>We’ll sync the dashboard as soon as you save.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('salary-plans.update', $salaryPlan->id) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label for="month">Target month</label>
                        <input id="month" type="month" name="month" value="{{ old('month', $salaryPlan->month) }}" class="planner-input" required autofocus>
                        <x-input-error :messages="$errors->get('month')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="income_amount">Monthly salary / income</label>
                        <input id="income_amount" type="number" step="0.01" min="0" name="income_amount" value="{{ old('income_amount', $currentIncome ?? 0) }}" class="planner-input" data-income-input data-planner-input placeholder="0.00">
                        <x-input-error :messages="$errors->get('income_amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="savings_amount">Savings commitment</label>
                        <input id="savings_amount" type="number" step="0.01" min="0" name="savings_amount" value="{{ old('savings_amount', $currentSavings ?? 0) }}" class="planner-input" data-savings-input data-planner-input placeholder="0.00">
                        <x-input-error :messages="$errors->get('savings_amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="planner-select" required>
                            <option value="Active" {{ $salaryPlan->status === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Completed" {{ $salaryPlan->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ $salaryPlan->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label>Planned expenses</label>
                        <p class="text-sm text-gray-500">Update your expense amounts by category. Leave blank if not needed.</p>
                        <div class="expense-grid">
                            @foreach($quickCategories ?? [] as $key => $label)
                                <div class="expense-card">
                                    <span>{{ $label }}</span>
                                    <input type="number" step="0.01" min="0" name="expense_inputs[{{ $key }}]" value="{{ old("expense_inputs.$key", $currentExpenses[$key] ?? 0) }}" class="planner-input" data-expense-input data-planner-input placeholder="0.00">
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('expense_inputs')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes or adjustments</label>
                        <textarea id="notes" name="notes" class="planner-textarea" placeholder="Log assumptions, reminders, or new priorities.">{{ old('notes', $salaryPlan->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="planner-summary">
                        <div class="summary-chip">
                            <span>Income</span>
                            <strong data-summary-income>PKR0.00</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Expenses</span>
                            <strong data-summary-expenses>PKR0.00</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Savings</span>
                            <strong data-summary-savings>PKR0.00</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Remaining</span>
                            <strong data-summary-remaining>PKR0.00</strong>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('salary-plans.show', $salaryPlan->id) }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Save updates</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const incomeInput = document.querySelector('[data-income-input]');
            const savingsInput = document.querySelector('[data-savings-input]');
            const expenseInputs = document.querySelectorAll('[data-expense-input]');
            const summary = {
                income: document.querySelector('[data-summary-income]'),
                expenses: document.querySelector('[data-summary-expenses]'),
                savings: document.querySelector('[data-summary-savings]'),
                remaining: document.querySelector('[data-summary-remaining]'),
            };

            const numberFormatter = new Intl.NumberFormat('en-PK', {
                style: 'currency',
                currency: 'PKR',
                minimumFractionDigits: 2,
            });

            function recalc() {
                const income = parseFloat(incomeInput?.value) || 0;
                const savings = parseFloat(savingsInput?.value) || 0;
                let expenses = 0;
                expenseInputs.forEach((input) => {
                    expenses += parseFloat(input.value) || 0;
                });
                const remaining = income - (expenses + savings);

                summary.income.textContent = numberFormatter.format(income);
                summary.expenses.textContent = numberFormatter.format(expenses);
                summary.savings.textContent = numberFormatter.format(savings);
                summary.remaining.textContent = numberFormatter.format(remaining);
            }

            const plannerInputs = document.querySelectorAll('[data-planner-input]');
            plannerInputs.forEach((input) => input.addEventListener('input', recalc));
            recalc();
        });
    </script>
</x-app-layout>

