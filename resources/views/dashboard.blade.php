<x-app-layout>

    <div class="dashboard-shell">
        <x-planner-sidebar active="dashboard" subtitle="Money HQ" :showUser="true" />

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

            <section class="panel-grid">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Loans Overview</h3>
                        <a href="{{ route('loans.index') }}" style="font-weight:700; color:var(--primary); text-decoration:none;">
                            View all →
                        </a>
                    </div>
                    <div class="stats-grid" style="grid-template-columns:repeat(2, 1fr); margin-top:16px;">
                        <div class="stat-card">
                            <div class="stat-label">Owed to Me</div>
                            <div class="stat-value" style="color:#16a34a;">PKR{{ number_format($loansOwedToMe ?? 0, 2) }}</div>
                            <div class="stat-hint">Total: PKR{{ number_format($totalLoansOwedToMe ?? 0, 2) }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">I Owe</div>
                            <div class="stat-value" style="color:#dc2626;">PKR{{ number_format($loansIOwe ?? 0, 2) }}</div>
                            <div class="stat-hint">Total: PKR{{ number_format($totalLoansIOwe ?? 0, 2) }}</div>
                        </div>
                    </div>
                    @if(isset($recentLoans) && $recentLoans->count() > 0)
                        <div style="margin-top:20px; padding-top:20px; border-top:1px solid rgba(0,0,0,0.1);">
                            <div style="font-size:0.85rem; color:var(--muted); margin-bottom:12px; font-weight:600;">Recent Loans</div>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($recentLoans as $loan)
                                    <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0;">
                                        <div>
                                            <div style="font-weight:600; color:var(--text-dark);">{{ $loan->person_name }}</div>
                                            <div style="font-size:0.75rem; color:var(--muted);">
                                                <span class="badge {{ $loan->loan_type === 'owed_to_me' ? 'badge-success' : 'badge-danger' }}" style="font-size:0.7rem; padding:2px 6px;">
                                                    {{ $loan->loan_type === 'owed_to_me' ? 'Owed to Me' : 'I Owe' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-weight:600; color:{{ $loan->remaining_amount > 0 ? '#dc2626' : '#16a34a' }};">
                                                PKR{{ number_format($loan->remaining_amount, 2) }}
                                            </div>
                                            <div style="font-size:0.7rem; color:var(--muted);">{{ $loan->status }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h3>Investments Overview</h3>
                        <a href="{{ route('investments.index') }}" style="font-weight:700; color:var(--primary); text-decoration:none;">
                            View all →
                        </a>
                    </div>
                    <div class="stats-grid" style="grid-template-columns:repeat(2, 1fr); margin-top:16px;">
                        <div class="stat-card">
                            <div class="stat-label">Total Invested</div>
                            <div class="stat-value" style="color:#2563eb;">PKR{{ number_format($totalInvested ?? 0, 2) }}</div>
                            <div class="stat-hint">Withdrawn: PKR{{ number_format($totalWithdrawn ?? 0, 2) }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Current Value</div>
                            <div class="stat-value {{ ($totalCurrentValue ?? 0) >= ($totalInvested ?? 0) - ($totalWithdrawn ?? 0) ? 'positive' : 'negative' }}">
                                PKR{{ number_format($totalCurrentValue ?? 0, 2) }}
                            </div>
                            <div class="stat-hint">
                                Profit: PKR{{ number_format($totalInvestmentProfit ?? 0, 2) }} · 
                                Loss: PKR{{ number_format($totalInvestmentLoss ?? 0, 2) }}
                            </div>
                        </div>
                    </div>
                    @if(isset($recentInvestments) && $recentInvestments->count() > 0)
                        <div style="margin-top:20px; padding-top:20px; border-top:1px solid rgba(0,0,0,0.1);">
                            <div style="font-size:0.85rem; color:var(--muted); margin-bottom:12px; font-weight:600;">Recent Investments</div>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($recentInvestments as $investment)
                                    <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0;">
                                        <div>
                                            <div style="font-weight:600; color:var(--text-dark);">{{ $investment->investment_name }}</div>
                                            <div style="font-size:0.75rem; color:var(--muted);">
                                                <span class="badge {{ $investment->status === 'Active' ? 'badge-success' : 'badge-gray' }}" style="font-size:0.7rem; padding:2px 6px;">
                                                    {{ $investment->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-weight:600; color:{{ $investment->current_value >= ($investment->total_invested - $investment->total_withdrawn) ? '#16a34a' : '#dc2626' }};">
                                                PKR{{ number_format($investment->current_value, 2) }}
                                            </div>
                                            <div style="font-size:0.7rem; color:var(--muted);">
                                                Invested: PKR{{ number_format($investment->total_invested, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
