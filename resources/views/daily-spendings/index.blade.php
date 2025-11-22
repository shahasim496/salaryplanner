<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="daily-spendings" subtitle="Daily spending" />

        <main class="planner-main">
            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(37,99,235,0.1); color:var(--accent);">Daily Spending</p>
                        <h2>Track Your Daily Expenses</h2>
                        <span>Record daily spending linked to your salary plans. Amounts will update plan expenses automatically.</span>
                    </div>
                    <a href="{{ route('daily-spendings.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px; color:white;">
                        <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span style="color:white;">Add Spending</span>
                    </a>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('daily-spendings.index') }}" class="form-grid" style="margin-top:22px; padding:20px; background:var(--bg-light); border-radius:12px;">
                    <div class="form-field">
                        <label for="plan_id">Filter by Plan</label>
                        <select id="plan_id" name="plan_id" class="planner-select" onchange="this.form.submit()">
                            <option value="">All Plans</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->month }} - PKR{{ number_format($plan->total_income, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="date_from">From Date</label>
                        <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}" class="planner-input" onchange="this.form.submit()">
                    </div>
                    <div class="form-field">
                        <label for="date_to">To Date</label>
                        <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}" class="planner-input" onchange="this.form.submit()">
                    </div>
                    <div class="form-field">
                        <label>&nbsp;</label>
                        <a href="{{ route('daily-spendings.index') }}" class="ghost-btn" style="display:inline-block; text-align:center;">Clear Filters</a>
                    </div>
                </form>

                @if(isset($totalSpending))
                    <div class="stats-grid" style="margin-top:22px;">
                        <div class="stat-card">
                            <span>Total Spending</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($totalSpending, 2) }}</strong>
                            <small style="color:var(--muted);">{{ $dailySpendings->total() }} records</small>
                        </div>
                        @if($spendingByCategory->count() > 0)
                            <div class="stat-card">
                                <span>Top Category</span>
                                <strong style="color:#7c3aed;">{{ $spendingByCategory->first()->category }}</strong>
                                <small style="color:var(--muted);">PKR{{ number_format($spendingByCategory->first()->total, 2) }}</small>
                            </div>
                        @endif
                    </div>
                @endif

                @if($dailySpendings->count())
                    <div class="table-wrapper" style="margin-top:22px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Plan</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailySpendings as $spending)
                                    <tr>
                                        <td style="font-weight:600; color:var(--text-dark);">{{ \Carbon\Carbon::parse($spending->spending_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $spending->salaryPlan->month }}</span>
                                        </td>
                                        <td style="font-weight:600;">{{ $spending->category }}</td>
                                        <td style="font-weight:600; color:#dc2626;">PKR{{ number_format($spending->amount, 2) }}</td>
                                        <td style="color:var(--muted);">{{ $spending->description ?: '-' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('daily-spendings.edit', $spending->id) }}" class="action-btn ghost-btn">Edit</a>
                                                <form method="POST" action="{{ route('daily-spendings.destroy', $spending->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this spending record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn" style="color:#dc2626; border-color:#dc2626;">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:24px;">
                        {{ $dailySpendings->appends(request()->query())->links() }}
                    </div>
                @else
                    <div style="text-align:center; padding:48px 0;">
                        <p style="color:var(--muted); margin-bottom:16px;">No daily spending records yet. Add your first spending to get started.</p>
                        <a href="{{ route('daily-spendings.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px;">
                            <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Spending</span>
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-app-layout>

