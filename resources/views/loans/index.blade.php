<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="loans" subtitle="Loans management" />

        <main class="planner-main">
            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(147,51,234,0.1); color:#7c3aed;">Loans</p>
                        <h2>Loan Management</h2>
                        <span>Track loans by person. Each person can have multiple loan entries and payments.</span>
                    </div>
                    <a href="{{ route('loans.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px; color:white;">
                        <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span style="color:white;">Add Person</span>
                    </a>
                </div>

                @if(isset($summary))
                    <div class="stats-grid" style="margin-top:22px;">
                        <div class="stat-card">
                            <span>Owed to Me</span>
                            <strong style="color:#16a34a;">PKR{{ number_format($summary['owed_to_me_total'], 2) }}</strong>
                            <small style="color:var(--muted);">Paid: PKR{{ number_format($summary['owed_to_me_paid'], 2) }}</small>
                        </div>
                        <div class="stat-card">
                            <span>I Owe</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($summary['owed_by_me_total'], 2) }}</strong>
                            <small style="color:var(--muted);">Paid: PKR{{ number_format($summary['owed_by_me_paid'], 2) }}</small>
                        </div>
                    </div>
                @endif

                @if($loans->count())
                    <div class="table-wrapper" style="margin-top:22px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Person</th>
                                    <th>Type</th>
                                    <th>Total Loaned</th>
                                    <th>Total Paid</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td style="font-weight:600; color:var(--text-dark);">{{ $loan->person_name }}</td>
                                        <td>
                                            <span class="badge {{ $loan->loan_type === 'owed_to_me' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $loan->loan_type === 'owed_to_me' ? 'Owed to Me' : 'I Owe' }}
                                            </span>
                                        </td>
                                        <td style="font-weight:600;">PKR{{ number_format($loan->total_loaned, 2) }}</td>
                                        <td style="color:#16a34a;">PKR{{ number_format($loan->total_paid, 2) }}</td>
                                        <td style="font-weight:600; color:{{ $loan->remaining_amount > 0 ? '#dc2626' : '#16a34a' }};">PKR{{ number_format($loan->remaining_amount, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $loan->status === 'Paid' ? 'badge-success' : ($loan->status === 'Partial' ? 'badge-warning' : 'badge-info') }}">
                                                {{ $loan->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('loans.show', $loan->id) }}" class="action-btn primary-btn">View</a>
                                                <a href="{{ route('loans.edit', $loan->id) }}" class="action-btn ghost-btn">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:24px;">
                        {{ $loans->links() }}
                    </div>
                @else
                    <div style="text-align:center; padding:48px 0;">
                        <p style="color:var(--muted); margin-bottom:16px;">No loan records yet. Add a person to get started.</p>
                        <a href="{{ route('loans.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px;">
                            <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Person</span>
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-app-layout>
