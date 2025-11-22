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
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.1);
            color: #d97706;
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .chart-wrapper {
            margin-top: 22px;
            height: 300px;
            position: relative;
        }

        .chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .section-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--text-dark);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn-danger {
            color: #dc2626;
            background: rgba(239, 68, 68, 0.1);
        }

        .action-btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 50;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--panel-bg);
            border-radius: 24px;
            padding: 32px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--text-dark);
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 4px;
        }

        .form-field {
            margin-bottom: 18px;
        }

        .form-field label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-field input,
        .form-field select,
        .form-field textarea {
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--panel-border);
            padding: 12px 16px;
            font-size: 1rem;
            color: var(--text-dark);
            background: #f8f9ff;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        .form-field input:focus,
        .form-field select:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            background: #fff;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
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
                    <small style="opacity:0.7;">Plan details</small>
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
                <a href="{{ route('salary-plans.index') }}" class="sidebar-link">
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
            <section class="planner-hero">
                <div style="max-width:520px;">
                    <span class="planner-pill">Plan overview · {{ \Carbon\Carbon::parse($salaryPlan->month)->format('F Y') }}</span>
                    <h1>Complete financial breakdown for this month.</h1>
                    <p>Review income, expenses, savings, and track your financial progress.</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span>Total Income</span>
                            <strong>PKR{{ number_format($netIncome, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Planned Expenses</span>
                            <strong>PKR{{ number_format($totalPlannedExpenses, 2) }}</strong>
                            <small style="display:block; margin-top:4px; font-size:0.75rem; color:var(--muted);">
                                Paid: PKR{{ number_format($paidExpenses ?? 0, 2) }}
                            </small>
                        </div>
                        <div class="stat-card">
                            <span>Planned Savings</span>
                            <strong>PKR{{ number_format($totalPlannedSavings, 2) }}</strong>
                            <small style="display:block; margin-top:4px; font-size:0.75rem; color:var(--muted);">
                                Completed: PKR{{ number_format($completedSavings ?? 0, 2) }}
                            </small>
                        </div>
                        <div class="stat-card">
                            <span>Remaining</span>
                            <strong style="color: {{ $remainingAmount >= 0 ? '#7c3aed' : '#dc2626' }};">PKR{{ number_format($remainingAmount, 2) }}</strong>
                            <small style="display:block; margin-top:4px; font-size:0.75rem; color:var(--muted);">
                                After paid/completed only
                            </small>
                        </div>
                    </div>
                </div>
                <div class="planner-panel" style="flex:0 0 280px;">
                    <div class="panel-head">
                        <span style="font-size:0.8rem; letter-spacing:0.14em; color:var(--muted); text-transform:uppercase;">Quick actions</span>
                        <h2 style="font-size:1.2rem; margin:4px 0 14px;">Manage plan</h2>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <a href="{{ route('salary-plans.edit', $salaryPlan->id) }}" class="primary-btn" style="text-align:center;">Edit Plan</a>
                        <a href="{{ route('salary-plans.index') }}" class="ghost-btn" style="text-align:center;">Back to Plans</a>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Charts Section -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:22px;">
                <section class="planner-panel">
                    <div class="panel-head">
                        <p class="planner-pill" style="background:rgba(34,197,94,0.1); color:#16a34a;">Visualization</p>
                        <h2>Income vs Expenses vs Savings</h2>
                        <span>Compare your financial categories at a glance.</span>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="summaryChart"></canvas>
                    </div>
                </section>
                <section class="planner-panel">
                    <div class="panel-head">
                        <p class="planner-pill" style="background:rgba(239,68,68,0.1); color:#dc2626;">Breakdown</p>
                        <h2>Expenses by Category</h2>
                        <span>See where your money is going.</span>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </section>
            </div>

            <!-- Salary Items Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(59,130,246,0.1); color:#2563eb;">Income & Deductions</p>
                        <h3>Salary Items</h3>
                    </div>
                    <button onclick="document.getElementById('addSalaryItemModal').classList.add('active')" class="primary-btn">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Item
                    </button>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaryPlan->salaryItems as $item)
                                <tr>
                                    <td style="font-weight:600; color:var(--text-dark);">{{ $item->item_name }}</td>
                                    <td>
                                        <span class="badge {{ $item->item_type === 'Income' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $item->item_type }}
                                        </span>
                                    </td>
                                    <td style="color:var(--muted);">{{ $item->category ?? '-' }}</td>
                                    <td style="font-weight:600; color:{{ $item->item_type === 'Income' ? '#16a34a' : '#dc2626' }};">
                                        PKR{{ number_format($item->amount, 2) }}
                                    </td>
                                    <td>
                                        <form action="{{ route('salary-plans.delete-item', [$salaryPlan->id, $item->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this item?')">
                                                <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:40px; color:var(--muted);">
                                        <p>No salary items added yet.</p>
                                        <button onclick="document.getElementById('addSalaryItemModal').classList.add('active')" class="primary-btn" style="margin-top:12px;">
                                            Add your first item
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Expenses Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(239,68,68,0.1); color:#dc2626;">Spending</p>
                        <h3>Expenses</h3>
                    </div>
                    <button onclick="document.getElementById('addExpenseModal').classList.add('active')" class="primary-btn">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Expense
                    </button>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Planned</th>
                                <th>Actual</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaryPlan->expenses as $expense)
                                <tr>
                                    <td style="font-weight:600; color:var(--text-dark);">{{ $expense->expense_name }}</td>
                                    <td style="color:var(--muted);">{{ $expense->category }}</td>
                                    <td style="font-weight:600; color:var(--text-dark);">PKR{{ number_format($expense->planned_amount, 2) }}</td>
                                    <td style="font-weight:600; color:var(--text-dark);">PKR{{ number_format($expense->actual_amount ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $isCompleted = $expense->actual_amount && $expense->actual_amount >= $expense->planned_amount;
                                            $status = $isCompleted ? 'Completed' : ($expense->is_paid ? 'Paid' : 'Pending');
                                            $badgeClass = $isCompleted ? 'badge-success' : ($expense->is_paid ? 'badge-info' : 'badge-warning');
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="openEditExpenseModal({{ $expense->id }}, '{{ $expense->expense_name }}', '{{ $expense->category }}', {{ $expense->planned_amount }}, {{ $expense->actual_amount ?? 0 }}, {{ $expense->is_paid ? 'true' : 'false' }})" class="action-btn" style="background:rgba(37,99,235,0.1); color:#2563eb;">
                                                <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('salary-plans.delete-expense', [$salaryPlan->id, $expense->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this expense?')">
                                                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:40px; color:var(--muted);">
                                        <p>No expenses added yet.</p>
                                        <button onclick="document.getElementById('addExpenseModal').classList.add('active')" class="primary-btn" style="margin-top:12px;">
                                            Add your first expense
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Savings Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(59,130,246,0.1); color:#2563eb;">Goals</p>
                        <h3>Savings Goals</h3>
                    </div>
                    <button onclick="document.getElementById('addSavingModal').classList.add('active')" class="primary-btn">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Saving Goal
                    </button>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Planned</th>
                                <th>Actual</th>
                                <th>Target</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaryPlan->savings as $saving)
                                <tr>
                                    <td style="font-weight:600; color:var(--text-dark);">{{ $saving->saving_name }}</td>
                                    <td style="color:var(--muted);">{{ $saving->saving_type }}</td>
                                    <td style="font-weight:600; color:#2563eb;">PKR{{ number_format($saving->planned_amount, 2) }}</td>
                                    <td style="font-weight:600; color:#16a34a;">PKR{{ number_format($saving->actual_amount ?? 0, 2) }}</td>
                                    <td style="font-weight:600; color:var(--text-dark);">PKR{{ number_format($saving->target_amount ?? 0, 2) }}</td>
                                    <td>
                                        @if($saving->target_amount)
                                            @php
                                                $progress = ($saving->accumulated_amount / $saving->target_amount) * 100;
                                            @endphp
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <div style="flex:1; height:8px; background:#e2e8f0; border-radius:999px; overflow:hidden;">
                                                    <div style="height:100%; background:linear-gradient(90deg, #2563eb, #7c3aed); width:{{ min($progress, 100) }}%; transition:width 0.3s;"></div>
                                                </div>
                                                <span style="font-size:0.85rem; color:var(--muted); font-weight:600;">{{ number_format($progress, 1) }}%</span>
                                            </div>
                                        @else
                                            <span style="color:var(--muted);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="openEditSavingModal({{ $saving->id }}, '{{ $saving->saving_name }}', '{{ $saving->saving_type }}', {{ $saving->planned_amount }}, {{ $saving->actual_amount ?? 0 }}, {{ $saving->target_amount ?? 0 }})" class="action-btn" style="background:rgba(37,99,235,0.1); color:#2563eb;">
                                                <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('salary-plans.delete-saving', [$salaryPlan->id, $saving->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this saving goal?')">
                                                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">
                                        <p>No savings goals added yet.</p>
                                        <button onclick="document.getElementById('addSavingModal').classList.add('active')" class="primary-btn" style="margin-top:12px;">
                                            Add your first saving goal
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Add Salary Item Modal -->
    <div id="addSalaryItemModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Salary Item</h3>
                <button class="modal-close" onclick="document.getElementById('addSalaryItemModal').classList.remove('active')">
                    <svg style="width:24px; height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('salary-plans.add-item', $salaryPlan->id) }}">
                @csrf
                <div class="form-field">
                    <label for="item_name">Item Name</label>
                    <input id="item_name" type="text" name="item_name" required>
                </div>
                <div class="form-field">
                    <label for="item_type">Type</label>
                    <select id="item_type" name="item_type" required>
                        <option value="Income">Income</option>
                        <option value="Deduction">Deduction</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="category">Category</label>
                    <input id="category" type="text" name="category">
                </div>
                <div class="form-field">
                    <label for="amount">Amount</label>
                    <input id="amount" type="number" step="0.01" name="amount" required>
                </div>
                <div class="form-field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="ghost-btn" onclick="document.getElementById('addSalaryItemModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="primary-btn">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="addExpenseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Expense</h3>
                <button class="modal-close" onclick="document.getElementById('addExpenseModal').classList.remove('active')">
                    <svg style="width:24px; height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('salary-plans.add-expense', $salaryPlan->id) }}">
                @csrf
                <div class="form-field">
                    <label for="expense_name">Expense Name</label>
                    <input id="expense_name" type="text" name="expense_name" required>
                </div>
                <div class="form-field">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="Food">Food</option>
                        <option value="Transport">Transport</option>
                        <option value="Bills">Bills</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="planned_amount">Planned Amount</label>
                    <input id="planned_amount" type="number" step="0.01" name="planned_amount" required>
                </div>
                <div class="form-field">
                    <label for="due_date">Due Date</label>
                    <input id="due_date" type="date" name="due_date">
                </div>
                <div class="form-actions">
                    <button type="button" class="ghost-btn" onclick="document.getElementById('addExpenseModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="primary-btn">Add Expense</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Saving Modal -->
    <div id="addSavingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Saving Goal</h3>
                <button class="modal-close" onclick="document.getElementById('addSavingModal').classList.remove('active')">
                    <svg style="width:24px; height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('salary-plans.add-saving', $salaryPlan->id) }}">
                @csrf
                <div class="form-field">
                    <label for="saving_name">Saving Name</label>
                    <input id="saving_name" type="text" name="saving_name" required>
                </div>
                <div class="form-field">
                    <label for="saving_type">Type</label>
                    <select id="saving_type" name="saving_type" required>
                        <option value="Fixed">Fixed</option>
                        <option value="Variable">Variable</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Investment">Investment</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="planned_amount">Planned Amount</label>
                    <input id="planned_amount" type="number" step="0.01" name="planned_amount" required>
                </div>
                <div class="form-field">
                    <label for="target_amount">Target Amount (Optional)</label>
                    <input id="target_amount" type="number" step="0.01" name="target_amount">
                </div>
                <div class="form-field">
                    <label for="target_date">Target Date (Optional)</label>
                    <input id="target_date" type="date" name="target_date">
                </div>
                <div class="form-actions">
                    <button type="button" class="ghost-btn" onclick="document.getElementById('addSavingModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="primary-btn">Add Saving</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div id="editExpenseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Expense</h3>
                <button class="modal-close" onclick="document.getElementById('editExpenseModal').classList.remove('active')">
                    <svg style="width:24px; height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editExpenseForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-field">
                    <label for="edit_expense_name">Expense Name</label>
                    <input id="edit_expense_name" type="text" name="expense_name" required>
                </div>
                <div class="form-field">
                    <label for="edit_category">Category</label>
                    <select id="edit_category" name="category" required>
                        <option value="Food">Food</option>
                        <option value="Transport">Transport</option>
                        <option value="Bills">Bills</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="edit_planned_amount">Planned Amount</label>
                    <input id="edit_planned_amount" type="number" step="0.01" name="planned_amount" required>
                </div>
                <div class="form-field">
                    <label for="edit_actual_amount">Actual Amount</label>
                    <input id="edit_actual_amount" type="number" step="0.01" name="actual_amount" placeholder="Enter actual amount when paid">
                </div>
                <div class="form-field">
                    <label for="edit_is_paid" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input id="edit_is_paid" type="checkbox" name="is_paid" value="1" style="width:auto;">
                        <span>Mark as Paid</span>
                    </label>
                </div>
                <div class="form-actions">
                    <button type="button" class="ghost-btn" onclick="document.getElementById('editExpenseModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="primary-btn">Update Expense</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Saving Modal -->
    <div id="editSavingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Saving Goal</h3>
                <button class="modal-close" onclick="document.getElementById('editSavingModal').classList.remove('active')">
                    <svg style="width:24px; height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editSavingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-field">
                    <label for="edit_saving_name">Saving Name</label>
                    <input id="edit_saving_name" type="text" name="saving_name" required>
                </div>
                <div class="form-field">
                    <label for="edit_saving_type">Type</label>
                    <select id="edit_saving_type" name="saving_type" required>
                        <option value="Fixed">Fixed</option>
                        <option value="Variable">Variable</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Investment">Investment</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="edit_saving_planned_amount">Planned Amount</label>
                    <input id="edit_saving_planned_amount" type="number" step="0.01" name="planned_amount" required>
                </div>
                <div class="form-field">
                    <label for="edit_saving_actual_amount">Actual Amount</label>
                    <input id="edit_saving_actual_amount" type="number" step="0.01" name="actual_amount" placeholder="Enter actual amount saved">
                </div>
                <div class="form-field">
                    <label for="edit_saving_target_amount">Target Amount (Optional)</label>
                    <input id="edit_saving_target_amount" type="number" step="0.01" name="target_amount">
                </div>
                <div class="form-actions">
                    <button type="button" class="ghost-btn" onclick="document.getElementById('editSavingModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="primary-btn">Update Saving</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function openEditExpenseModal(expenseId, name, category, plannedAmount, actualAmount, isPaid) {
            document.getElementById('editExpenseForm').action = '{{ route("salary-plans.update-expense", [$salaryPlan->id, ":id"]) }}'.replace(':id', expenseId);
            document.getElementById('edit_expense_name').value = name;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_planned_amount').value = plannedAmount;
            document.getElementById('edit_actual_amount').value = actualAmount || '';
            document.getElementById('edit_is_paid').checked = isPaid === true || isPaid === 'true';
            document.getElementById('editExpenseModal').classList.add('active');
        }

        function openEditSavingModal(savingId, name, type, plannedAmount, actualAmount, targetAmount) {
            document.getElementById('editSavingForm').action = '{{ route("salary-plans.update-saving", [$salaryPlan->id, ":id"]) }}'.replace(':id', savingId);
            document.getElementById('edit_saving_name').value = name;
            document.getElementById('edit_saving_type').value = type;
            document.getElementById('edit_saving_planned_amount').value = plannedAmount;
            document.getElementById('edit_saving_actual_amount').value = actualAmount || '';
            document.getElementById('edit_saving_target_amount').value = targetAmount || '';
            document.getElementById('editSavingModal').classList.add('active');
        }

        // Auto-mark as paid when actual amount equals or exceeds planned amount
        document.getElementById('edit_actual_amount')?.addEventListener('input', function() {
            const actualAmount = parseFloat(this.value) || 0;
            const plannedAmount = parseFloat(document.getElementById('edit_planned_amount').value) || 0;
            if (actualAmount > 0 && actualAmount >= plannedAmount) {
                document.getElementById('edit_is_paid').checked = true;
            }
        });

        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        
        // Summary Chart
        const summaryCtx = document.getElementById('summaryChart').getContext('2d');
        new Chart(summaryCtx, {
            type: 'bar',
            data: {
                labels: ['Summary'],
                datasets: [
                    {
                        label: 'Income',
                        data: [{{ $netIncome }}],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderRadius: 8
                    },
                    {
                        label: 'Expenses',
                        data: [{{ $totalPlannedExpenses }}],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 8
                    },
                    {
                        label: 'Savings',
                        data: [{{ $totalPlannedSavings }}],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Expense Category Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseData = @json($expenseByCategory);
        new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: expenseData.map(item => item.category),
                datasets: [{
                    data: expenseData.map(item => parseFloat(item.total)),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(14, 165, 233, 0.8)',
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
