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

        .planner-sidebar {
            width: 260px;
            background: radial-gradient(circle at top, #2244c7, #0d1740);
            border-radius: 28px;
            padding: 30px 22px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: sticky;
            top: 32px;
            align-self: flex-start;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand .brand-mark {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link,
        .sidebar-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            transition: 0.2s ease;
        }

        .sidebar-link.active,
        .sidebar-link:hover,
        .sidebar-button:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .sidebar-link svg,
        .sidebar-button svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-button {
            width: 100%;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .stat-card {
            border-radius: 20px;
            border: 1px solid var(--panel-border);
            padding: 16px;
            background: #f8f9ff;
        }

        .stat-card span {
            display: block;
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .stat-card strong {
            font-size: 1.4rem;
            color: var(--text-dark);
        }

        .primary-btn {
            border-radius: 14px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            color: #fff;
            background: linear-gradient(120deg, #2563eb, #7c3aed);
            box-shadow: 0 18px 28px rgba(79, 70, 229, 0.25);
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .ghost-btn {
            border-radius: 14px;
            padding: 10px 20px;
            font-weight: 600;
            color: var(--muted);
            border: 1px solid var(--panel-border);
            background: #fff;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .primary-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 32px rgba(79, 70, 229, 0.3);
        }

        .ghost-btn:hover {
            background: #f8f9ff;
            border-color: var(--accent);
        }

        .table-wrapper {
            margin-top: 22px;
            overflow-x: auto;
        }

        .table-wrapper table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-wrapper thead {
            background: #f8f9ff;
        }

        .table-wrapper th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-wrapper td {
            padding: 14px 16px;
            border-top: 1px solid var(--panel-border);
            font-size: 0.95rem;
        }

        .table-wrapper tbody tr:hover {
            background: #f8f9ff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .badge-gray {
            background: rgba(100, 116, 139, 0.1);
            color: var(--muted);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        @media (max-width: 1024px) {
            .planner-shell {
                flex-direction: column;
            }

            .planner-sidebar {
                width: 100%;
                position: static;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .planner-sidebar nav {
                flex: 1 0 100%;
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
        <aside class="planner-sidebar">
            <div class="sidebar-brand">
                <div class="brand-mark">SP</div>
                <div>
                    <strong>Salary Planner</strong>
                    <small style="opacity:0.7;">All plans</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h4a1 1 0 001-1m-6 0V9" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('salary-plans.create') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Plan
                </a>
                <a href="{{ route('salary-plans.index') }}" class="sidebar-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 9h6M9 13h6m-9 4h12" />
                    </svg>
                    All Plans
                </a>
                @if (Route::has('profile.show'))
                    <a href="{{ route('profile.show') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                        </svg>
                        Log out
                    </button>
                </form>
            </nav>
        </aside>

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

