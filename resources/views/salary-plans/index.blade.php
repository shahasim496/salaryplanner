<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="index" subtitle="All plans" />

        <main class="planner-main">
           

            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(147,51,234,0.1); color:#7c3aed;">Your plans</p>
                        <h2>Monthly salary plans</h2>
                        <span>Listing only, with quick access to view or edit.</span>
                    </div>
                    <a href="{{ route('salary-plans.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px; color:white;">
                        <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span style="color:white;">Create Plan</span>
                    </a>
                </div>

                @if($salaryPlans->count())
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Income</th>
                                    <th>Expenses</th>
                                    <th>Savings</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaryPlans as $plan)
                                    <tr>
                                        <td style="font-weight:600; color:var(--text-dark);">{{ $plan->month }}</td>
                                        <td style="font-weight:600; color:#16a34a;">PKR{{ number_format($plan->total_income, 2) }}</td>
                                        <td style="font-weight:600; color:#dc2626;">PKR{{ number_format($plan->total_expenses, 2) }}</td>
                                        <td style="font-weight:600; color:#2563eb;">PKR{{ number_format($plan->total_savings, 2) }}</td>
                                        <td style="font-weight:600; color:{{ $plan->remaining_amount >= 0 ? '#7c3aed' : '#dc2626' }};">PKR{{ number_format($plan->remaining_amount, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $plan->status === 'Active' ? 'badge-success' : ($plan->status === 'Completed' ? 'badge-info' : 'badge-gray') }}">
                                                {{ $plan->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('salary-plans.show', $plan->id) }}" class="action-btn primary-btn">View</a>
                                                <a href="{{ route('salary-plans.edit', $plan->id) }}" class="action-btn ghost-btn">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:24px;">
                        {{ $salaryPlans->links() }}
                    </div>
                @else
                    <div style="text-align:center; padding:48px 0;">
                        <p style="color:var(--muted); margin-bottom:16px;">No salary plans yet. Create one to get started.</p>
                        <a href="{{ route('salary-plans.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px;">
                            <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Create Plan</span>
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-app-layout>

