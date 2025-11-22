<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="investments" subtitle="Investments" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Edit investment</p>
                        <h2>Update investment details</h2>
                        <span>Modify investment name, description, or status.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('investments.update', $investment->id) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="form-field">
                        <label for="investment_name">Investment Name</label>
                        <input id="investment_name" type="text" name="investment_name" value="{{ old('investment_name', $investment->investment_name) }}" class="planner-input" required autofocus>
                        <x-input-error :messages="$errors->get('investment_name')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="planner-select" required>
                            <option value="Active" {{ old('status', $investment->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Closed" {{ old('status', $investment->status) === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="planner-textarea">{{ old('description', $investment->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="planner-textarea">{{ old('notes', $investment->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="planner-summary">
                        <div class="summary-chip">
                            <span>Total Invested</span>
                            <strong>PKR{{ number_format($investment->total_invested, 2) }}</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Total Profit</span>
                            <strong style="color:#16a34a;">PKR{{ number_format($investment->total_profit, 2) }}</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Total Loss</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($investment->total_loss, 2) }}</strong>
                        </div>
                        <div class="summary-chip">
                            <span>Current Value</span>
                            <strong style="color:{{ $investment->current_value >= $investment->total_invested ? '#16a34a' : '#dc2626' }};">PKR{{ number_format($investment->current_value, 2) }}</strong>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('investments.show', $investment->id) }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Update Investment</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>

