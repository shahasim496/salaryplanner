<x-app-layout>
 

    <style>
        :root {
            --page-bg: #eef2ff;
            --panel-bg: #ffffff;
            --panel-border: #e2e8f0;
            --text-dark: #0f172a;
            --muted: #64748b;
            --primary: #2563eb;
            --accent: #f97316;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
        }

        .dashboard-shell {
            display: flex;
            gap: 28px;
            padding: 32px 32px 48px;
            min-height: calc(100vh - 120px);
            background: radial-gradient(circle at top, rgba(99, 102, 241, 0.08), rgba(15, 23, 42, 0.02));
        }

        .dashboard-sidebar {
            width: 270px;
            background: radial-gradient(circle at top, #2244c7, #0d1740);
            border-radius: 28px;
            padding: 32px 24px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 28px;
            position: sticky;
            top: 32px;
            align-self: flex-start;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-brand .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .sidebar-user {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-user span {
            display: block;
            font-weight: 700;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            transition: 0.2s ease;
        }

        .sidebar-link svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border-radius: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            background: transparent;
            border: 1px solid transparent;
            transition: 0.2s ease;
            cursor: pointer;
        }

        .sidebar-button svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-button:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .sidebar-link.active,
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .dashboard-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .main-hero {
            background: var(--panel-bg);
            border-radius: 32px;
            padding: 34px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .main-hero h1 {
            margin: 10px 0 16px;
            font-size: 2.4rem;
            line-height: 1.2;
            color: var(--text-dark);
        }

        .main-hero p {
            margin: 0;
            font-size: 1rem;
            color: var(--muted);
        }

        .hero-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .cta-btn {
            border-radius: 14px;
            padding: 14px 18px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .cta-btn.primary {
            background: linear-gradient(120deg, #2563eb, #7c3aed);
            color: #fff;
            box-shadow: 0 20px 35px rgba(79, 70, 229, 0.25);
        }

        .cta-btn.ghost {
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.2);
            background: #fff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 28px;
            padding: 24px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 12px 0;
        }

        .stat-value.positive {
            color: #7c3aed;
        }

        .stat-value.negative {
            color: #ef4444;
        }

        .stat-hint {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .panel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .panel {
            background: #fff;
            border-radius: 30px;
            padding: 28px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 22px;
        }

        .panel-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--text-dark);
        }

        .panel-header span {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .current-plan-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .summary-card {
            border-radius: 22px;
            padding: 20px;
            border: 1px solid var(--panel-border);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(99, 102, 241, 0.05));
        }

        .chart-wrapper {
            width: 100%;
        }

        .chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .chart-line {
            height: 220px;
        }

        .chart-donut {
            height: 240px;
        }

        .chart-bar {
            height: 210px;
        }

        @media (max-width: 1024px) {
            .dashboard-shell {
                flex-direction: column;
            }

            .dashboard-sidebar {
                width: 100%;
                position: static;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 18px;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar-link {
                flex: 1 0 160px;
            }
        }

        @media (max-width: 640px) {
            .dashboard-shell {
                padding: 18px;
            }

            .main-hero {
                padding: 24px;
            }

            .hero-cta {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <div class="sidebar-brand">
                <div class="brand-mark">SP</div>
            <div>
                    <strong>Salary Planner</strong>
                    <div style="font-size:0.8rem; opacity:0.7;">Money HQ</div>
                </div>
            </div>
            <div class="sidebar-user">
                <span>{{ Auth::user()->name }}</span>
                <small style="opacity:0.7;">Monthly strategist</small>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link active">
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

        <main class="dashboard-main">
         
            @php
                $remainingIsPositive = ($totalRemaining ?? 0) >= 0;
            @endphp

            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total earnings</div>
                    <div class="stat-value">PKR{{ number_format($totalIncome ?? 0, 2) }}</div>
                    <div class="stat-hint">Captured income streams</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total spending</div>
                    <div class="stat-value">PKR{{ number_format($totalExpenses ?? 0, 2) }}</div>
                    <div class="stat-hint">Tracked essentials + wants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total saved</div>
                    <div class="stat-value">PKR{{ number_format($totalSavings ?? 0, 2) }}</div>
                    <div class="stat-hint">Goals + safety nets</div>
            </div>
                <div class="stat-card">
                    <div class="stat-label">Available balance</div>
                    <div class="stat-value {{ $remainingIsPositive ? 'positive' : 'negative' }}">
                        PKR{{ number_format($totalRemaining ?? 0, 2) }}
                    </div>
                    <div class="stat-hint">After pay-yourself-first</div>
                </div>
            </section>

            @if ($currentPlan)
                <section class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Current month plan</h3>
                            <span>{{ $currentPlan->month }}</span>
                        </div>
                        <a href="{{ route('salary-plans.show', $currentPlan->id) }}" style="font-weight:700; color:var(--primary); text-decoration:none;">
                            View details →
                        </a>
                    </div>
                    <div class="current-plan-summary">
                        <div class="summary-card">
                            <div class="stat-label">Income</div>
                            <div class="stat-value" style="font-size:1.8rem;">PKR{{ number_format($currentPlan->total_income, 2) }}</div>
                        </div>
                        <div class="summary-card">
                            <div class="stat-label">Expenses</div>
                            <div class="stat-value" style="font-size:1.8rem;">PKR{{ number_format($currentPlan->total_expenses, 2) }}</div>
                        </div>
                        <div class="summary-card">
                            <div class="stat-label">Savings</div>
                            <div class="stat-value" style="font-size:1.8rem;">PKR{{ number_format($currentPlan->total_savings, 2) }}</div>
                    </div>
                    </div>
                </section>
            @endif

            <section class="panel-grid">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Monthly trend</h3>
                        <span>Income · Expenses · Savings</span>
                    </div>
                    <div class="chart-wrapper chart-line">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <h3>Expense by category</h3>
                        <span>Live doughnut</span>
                    </div>
                    <div class="chart-wrapper chart-donut">
                        <canvas id="expenseCategoryChart"></canvas>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <h3>Savings progress</h3>
                    <span>Planned vs actual</span>
            </div>
                <div class="chart-wrapper chart-bar">
                    <canvas id="savingsProgressChart"></canvas>
                </div>
            </section>
        </main>
        <div id="dashboard-data"
             data-monthly-trend='@json($monthlyTrend ?? [])'
             data-expense-categories='@json($expenseCategories ?? [])'
             data-savings-progress='@json($savingsProgress ?? [])'
             style="display:none;"></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.color = '#6B7280';
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 13;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 20;
        
        const dataRoot = document.getElementById('dashboard-data');
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        const monthlyTrendData = JSON.parse(dataRoot?.dataset?.monthlyTrend ?? '[]');
        
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: monthlyTrendData.map(item => item.month).reverse(),
                datasets: [
                    {
                        label: 'Income',
                        data: monthlyTrendData.map(item => item.total_income).reverse(),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Expenses',
                        data: monthlyTrendData.map(item => item.total_expenses).reverse(),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Savings',
                        data: monthlyTrendData.map(item => item.total_savings).reverse(),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        const expenseCategoryCtx = document.getElementById('expenseCategoryChart').getContext('2d');
        const expenseCategoryData = JSON.parse(dataRoot?.dataset?.expenseCategories ?? '[]');
        
        new Chart(expenseCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: expenseCategoryData.map(item => item.category),
                datasets: [{
                    data: expenseCategoryData.map(item => parseFloat(item.total)),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.9)',
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(34, 197, 94, 0.9)',
                        'rgba(251, 191, 36, 0.9)',
                        'rgba(168, 85, 247, 0.9)',
                        'rgba(236, 72, 153, 0.9)',
                        'rgba(249, 115, 22, 0.9)',
                        'rgba(14, 165, 233, 0.9)',
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            padding: 15
                        }
                    }
                }
            }
        });

        const savingsProgressCtx = document.getElementById('savingsProgressChart').getContext('2d');
        const savingsProgressData = JSON.parse(dataRoot?.dataset?.savingsProgress ?? '[]');
        
        new Chart(savingsProgressCtx, {
            type: 'bar',
            data: {
                labels: savingsProgressData.map(item => item.saving_type),
                datasets: [
                    {
                        label: 'Planned',
                        data: savingsProgressData.map(item => parseFloat(item.planned || 0)),
                        backgroundColor: 'rgba(59, 130, 246, 0.9)',
                        borderRadius: 10
                    },
                    {
                        label: 'Actual',
                        data: savingsProgressData.map(item => parseFloat(item.actual || 0)),
                        backgroundColor: 'rgba(34, 197, 94, 0.9)',
                        borderRadius: 10
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
