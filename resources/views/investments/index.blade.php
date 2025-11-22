<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="investments" subtitle="Investments" />

        <main class="planner-main">
            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(147,51,234,0.1); color:#7c3aed;">Investments</p>
                        <h2>Investment Management</h2>
                        <span>Track your investments, profits, losses, and manage investment amounts.</span>
                    </div>
                    <a href="{{ route('investments.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px; color:white;">
                        <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span style="color:white;">Add Investment</span>
                    </a>
                </div>

                @if(isset($summary))
                    <div class="stats-grid" style="margin-top:22px;">
                        <div class="stat-card">
                            <span>Total Invested</span>
                            <strong style="color:#2563eb;">PKR{{ number_format($summary['total_invested'], 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Withdrawn</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($summary['total_withdrawn'], 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Profit</span>
                            <strong style="color:#16a34a;">PKR{{ number_format($summary['total_profit'], 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Loss</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($summary['total_loss'], 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Current Value</span>
                            <strong style="color:{{ $summary['current_value'] >= ($summary['total_invested'] - $summary['total_withdrawn']) ? '#16a34a' : '#dc2626' }};">PKR{{ number_format($summary['current_value'], 2) }}</strong>
                        </div>
                    </div>
                @endif

                @if($investments->count())
                    <div class="table-wrapper" style="margin-top:22px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Investment Name</th>
                                    <th>Total Invested</th>
                                    <th>Withdrawn</th>
                                    <th>Profit</th>
                                    <th>Loss</th>
                                    <th>Current Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($investments as $investment)
                                    <tr>
                                        <td style="font-weight:600; color:var(--text-dark);">{{ $investment->investment_name }}</td>
                                        <td style="font-weight:600;">PKR{{ number_format($investment->total_invested, 2) }}</td>
                                        <td style="color:#dc2626; font-weight:600;">PKR{{ number_format($investment->total_withdrawn, 2) }}</td>
                                        <td style="color:#16a34a; font-weight:600;">PKR{{ number_format($investment->total_profit, 2) }}</td>
                                        <td style="color:#dc2626; font-weight:600;">PKR{{ number_format($investment->total_loss, 2) }}</td>
                                        <td style="font-weight:600; color:{{ $investment->current_value >= ($investment->total_invested - $investment->total_withdrawn) ? '#16a34a' : '#dc2626' }};">PKR{{ number_format($investment->current_value, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $investment->status === 'Active' ? 'badge-success' : 'badge-gray' }}">
                                                {{ $investment->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('investments.show', $investment->id) }}" class="action-btn primary-btn">View</a>
                                                <a href="{{ route('investments.edit', $investment->id) }}" class="action-btn ghost-btn">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:24px;">
                        {{ $investments->links() }}
                    </div>
                @else
                    <div style="text-align:center; padding:48px 0;">
                        <p style="color:var(--muted); margin-bottom:16px;">No investments recorded yet. Add an investment to get started.</p>
                        <a href="{{ route('investments.create') }}" class="primary-btn" style="display:inline-flex; align-items:center; gap:8px;">
                            <svg style="width:20px; height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Investment</span>
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-app-layout>

