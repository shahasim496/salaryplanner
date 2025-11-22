<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="daily-spendings" subtitle="Daily spending" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">New spending</p>
                        <h2>Add Daily Spending</h2>
                        <span>Record a daily expense linked to a salary plan. The amount will automatically update the plan's expense category.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('daily-spendings.store') }}" class="form-grid">
                    @csrf
                    <div class="form-field">
                        <label for="salary_plan_id">Salary Plan <span style="color:#dc2626;">*</span></label>
                        <select id="salary_plan_id" name="salary_plan_id" class="planner-select" required onchange="loadCategories(this.value)">
                            <option value="">Select a plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ (old('salary_plan_id', $selectedPlan?->id) == $plan->id) ? 'selected' : '' }}>
                                    {{ $plan->month }} - PKR{{ number_format($plan->total_income, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('salary_plan_id')" class="mt-1 text-sm text-red-500" />
                        <small style="color:var(--muted); margin-top:4px; display:block;">Select the plan this spending belongs to.</small>
                    </div>
                    <div class="form-field">
                        <label for="category">Category <span style="color:#dc2626;">*</span></label>
                        <select id="category" name="category" class="planner-select" required>
                            <option value="">Select a category</option>
                            @if($selectedPlan && count($categories) > 0)
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-1 text-sm text-red-500" />
                        <small style="color:var(--muted); margin-top:4px; display:block;">Categories are from the selected plan's expenses.</small>
                    </div>
                    <div class="form-field">
                        <label for="amount">Amount <span style="color:#dc2626;">*</span></label>
                        <input id="amount" type="number" name="amount" value="{{ old('amount') }}" class="planner-input" required step="0.01" min="0.01" placeholder="0.00">
                        <x-input-error :messages="$errors->get('amount')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="spending_date">Date <span style="color:#dc2626;">*</span></label>
                        <input id="spending_date" type="date" name="spending_date" value="{{ old('spending_date', date('Y-m-d')) }}" class="planner-input" required>
                        <x-input-error :messages="$errors->get('spending_date')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="planner-textarea" placeholder="Optional description for this spending.">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('daily-spendings.index') }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Add Spending</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        function loadCategories(planId) {
            if (!planId) {
                document.getElementById('category').innerHTML = '<option value="">Select a category</option>';
                return;
            }

            const baseUrl = '{{ url("/") }}';
            fetch(`${baseUrl}/salary-plans/${planId}/expenses`)
                .then(response => response.json())
                .then(data => {
                    const categorySelect = document.getElementById('category');
                    categorySelect.innerHTML = '<option value="">Select a category</option>';
                    
                    if (data.categories && data.categories.length > 0) {
                        data.categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category;
                            option.textContent = category;
                            categorySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No categories found in this plan';
                        categorySelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                    const categorySelect = document.getElementById('category');
                    categorySelect.innerHTML = '<option value="">Error loading categories</option>';
                });
        }
    </script>
</x-app-layout>

