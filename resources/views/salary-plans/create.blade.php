<x-app-layout>
    <x-slot name="header"></x-slot>

    <style>
        :root {
            --planner-bg: #eef2ff;
            --panel-bg: #ffffff;
            --panel-border: #e2e8f0;
            --text-dark: #0f172a;
            --muted: #64748b;
            --accent: #2563eb;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .planner-shell {
            display: flex;
            gap: 28px;
            padding: 32px 32px 48px;
            min-height: calc(100vh - 96px);
            background: radial-gradient(circle at top, rgba(99, 102, 241, 0.08), rgba(15, 23, 42, 0.02));
        }

        .planner-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .planner-hero {
            background: var(--panel-bg);
            border-radius: 32px;
            padding: 32px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .planner-hero h1 {
            margin: 0 0 12px;
            font-size: 2rem;
            color: var(--text-dark);
        }

        .planner-hero p {
            margin: 0;
            color: var(--muted);
        }

        .planner-pill {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.12);
            color: #f97316;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .planner-panel {
            background: var(--panel-bg);
            border-radius: 28px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
            padding: 28px;
        }

        .panel-head h2 {
            margin: 4px 0;
            font-size: 1.5rem;
            color: var(--text-dark);
        }

        .panel-head span {
            font-size: 0.95rem;
            color: var(--muted);
        }

        .form-grid {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-field label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .planner-input,
        .planner-textarea {
            width: 100%;
            border-radius: 16px;
            border: 1px solid var(--panel-border);
            padding: 14px 16px;
            font-size: 1rem;
            color: var(--text-dark);
            background: #f8f9ff;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        .planner-input:focus,
        .planner-textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            background: #fff;
        }

        .planner-textarea {
            min-height: 140px;
            resize: vertical;
        }

        .expense-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .expense-card {
            background: #f8f9ff;
            border: 1px solid var(--panel-border);
            border-radius: 18px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .expense-card span {
            font-weight: 600;
            color: var(--muted);
        }

        .planner-summary {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 6px;
        }

        .summary-chip {
            border-radius: 18px;
            border: 1px solid var(--panel-border);
            padding: 16px;
            background: #f8f9ff;
        }

        .summary-chip span {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .summary-chip strong {
            font-size: 1.4rem;
            color: var(--text-dark);
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 14px;
        }

        .ghost-btn {
            border-radius: 14px;
            padding: 12px 20px;
            font-weight: 600;
            color: var(--muted);
            border: 1px solid var(--panel-border);
            background: #fff;
        }

        .primary-btn {
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            color: #fff;
            background: linear-gradient(120deg, #2563eb, #7c3aed);
            box-shadow: 0 18px 28px rgba(79, 70, 229, 0.25);
            cursor: pointer;
        }

        .checklist {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 14px;
        }

        .checklist-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            color: var(--muted);
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .planner-shell {
                flex-direction: column;
            }

        }

        @media (max-width: 640px) {
            .planner-shell {
                padding: 20px;
            }

            .planner-panel {
                padding: 22px;
            }
        }
    </style>

    <div class="planner-shell">
        <x-planner-sidebar active="create" subtitle="Planning studio" />

        <main class="planner-main">
           

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Plan inputs</p>
                        <h2>Create salary plan</h2>
                        <span>We’ll use these details to populate dashboards and monthly summaries.</span>
                    </div>
                    </div>

                <form method="POST" action="{{ route('salary-plans.store') }}" class="form-grid">
                    @csrf
                    <div class="form-field">
                        <label for="month">Target month</label>
                        <input id="month" type="month" name="month" value="{{ old('month', date('Y-m')) }}" class="planner-input" required autofocus>
                        <x-input-error :messages="$errors->get('month')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="income_amount">Monthly salary / income</label>
                        <input id="income_amount" type="number" step="0.01" min="0" name="income_amount" value="{{ old('income_amount') }}" class="planner-input" data-income-input data-planner-input placeholder="0.00">
                        <x-input-error :messages="$errors->get('income_amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="savings_amount">Savings commitment</label>
                        <input id="savings_amount" type="number" step="0.01" min="0" name="savings_amount" value="{{ old('savings_amount') }}" class="planner-input" data-savings-input data-planner-input placeholder="0.00">
                        <x-input-error :messages="$errors->get('savings_amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label>Planned expenses</label>
                        <p class="text-sm text-gray-500">Distribute your salary across common categories. Leave blank if not needed.</p>
                        <div class="expense-grid">
                            @foreach($quickCategories as $key => $label)
                                <div class="expense-card">
                                    <span>{{ $label }}</span>
                                    <input type="number" step="0.01" min="0" name="expense_inputs[{{ $key }}]" value="{{ old("expense_inputs.$key") }}" class="planner-input" data-expense-input data-planner-input placeholder="0.00">
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('expense_inputs')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes or goals</label>
                        <textarea id="notes" name="notes" class="planner-textarea" placeholder="Capture priorities, reminders, or anything you want to keep in sight for this plan.">{{ old('notes') }}</textarea>
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
                        <a href="{{ route('salary-plans.index') }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Create plan</button>
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
