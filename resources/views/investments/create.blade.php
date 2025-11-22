<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="investments" subtitle="Investments" />

        <main class="planner-main">
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">New investment</p>
                        <h2>Create investment</h2>
                        <span>Create an investment record. You can add investment amounts and profit/loss records later.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('investments.store') }}" class="form-grid">
                    @csrf
                    <div class="form-field">
                        <label for="investment_name">Investment Name</label>
                        <input id="investment_name" type="text" name="investment_name" value="{{ old('investment_name') }}" class="planner-input" required autofocus placeholder="e.g., Stock Portfolio, Mutual Fund, Crypto">
                        <x-input-error :messages="$errors->get('investment_name')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="planner-textarea" placeholder="Describe this investment.">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-sm text-red-500" />
                    </div>
                    <div class="form-field" style="grid-column:1/-1;">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="planner-textarea" placeholder="Additional notes about this investment.">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1 text-sm text-red-500" />
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('investments.index') }}" class="ghost-btn">Cancel</a>
                        <button type="submit" class="primary-btn">Create Investment</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>

