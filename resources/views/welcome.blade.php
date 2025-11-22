<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Salary Planner | Plan every paycheck with clarity</title>
        <meta name="description" content="Welcome to Salary Planner—an organized workspace to plan how every paycheck will be saved, spent, and invested.">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/planner.css') }}">
    </head>
    <body>
        <div class="">
            <header class="top-nav">
                <div class="brand">
                    <div class="brand-mark">SP</div>
                    <div>
                        <h1>Salary Planner</h1>
                        <small style="color:var(--muted); font-weight:600;">Make every peso count</small>
                    </div>
                </div>
                <div class="nav-actions">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="ghost-btn">Log in</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="primary-btn">Register</a>
                    @endif
                </div>
            </header>

            <main class="content">
                <section class="hero">
                    <div>
                        <p class="pill" style="background:rgba(249,115,22,0.12); color:var(--accent);">Welcome back</p>
                        <h2>Plan how you'll use this month's salary before it hits your account.</h2>
                        <p>Break down your paycheck into essentials, goals, and guilt-free spending. Salary Planner keeps every decision visible so you stick to what matters most.</p>
                        <div class="cta-buttons">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="primary-btn">Register</a>
                            @endif
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="ghost-btn">Log in</a>
                            @endif
                        </div>
                    </div>
                    <div class="hero-summary">
                        <h3>Next Payday</h3>
                        <div class="payday">Nov 30 · PKR45,000</div>
                        <div class="snapshot-row">
                            <span>Allocated</span>
                            <span>PKR36,400</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:81%;"></div>
                        </div>
                        <div class="snapshot-row">
                            <span>Essentials</span>
                            <span>PKR18,200</span>
                        </div>
                        <div class="snapshot-row">
                            <span>Savings & Goals</span>
                            <span>PKR12,800</span>
                        </div>
                        <div class="snapshot-row">
                            <span>Flex Spending</span>
                            <span>PKR5,400</span>
                        </div>
                    </div>
                </section>

                <section class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Take-home pay</div>
                        <div class="stat-value">PKR45,000</div>
                        <div class="stat-hint">After tax & deductions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Bills & essentials</div>
                        <div class="stat-value">PKR18,200</div>
                        <div class="stat-hint">Wifi, rent, groceries, utilities</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Savings this month</div>
                        <div class="stat-value">PKR12,800</div>
                        <div class="stat-hint">Emergency + investments</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Left to enjoy</div>
                        <div class="stat-value">PKR8,600</div>
                        <div class="stat-hint">Dining, hobbies, small treats</div>
                    </div>
                </section>

                <section class="plan-grid">
                    <div class="plan-panel">
                        <div class="panel-head">
                            <h3>Monthly snapshot</h3>
                            <span>Updated today · 94% planned</span>
                        </div>
                        <div class="bar-list">
                            <div class="bar-item">
                                <label>
                                    <span>Income</span>
                                    <span>PKR45,000</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:100%;"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Essentials</span>
                                    <span>40%</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:40%;"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Savings & Goals</span>
                                    <span>28%</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill green" style="width:28%;"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Flex Spending</span>
                                    <span>22%</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:22%; background:linear-gradient(120deg,#f97316,#fb923c);"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="plan-panel">
                        <div class="panel-head">
                            <h3>Spending buckets</h3>
                            <span>Live envelope view</span>
                        </div>
                        <div class="bar-list">
                            <div class="bar-item">
                                <label>
                                    <span>Groceries & market</span>
                                    <span>PKR7,400 / 8,000</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:92%;"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Transport & fuel</span>
                                    <span>PKR2,100 / 3,000</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill green" style="width:70%;"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Dining out</span>
                                    <span>PKR1,450 / 3,500</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:41%; background:linear-gradient(120deg,#ec4899,#f472b6);"></div>
                                </div>
                            </div>
                            <div class="bar-item">
                                <label>
                                    <span>Personal growth</span>
                                    <span>PKR900 / 2,500</span>
                                </label>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:36%; background:linear-gradient(120deg,#a855f7,#7c3aed);"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="plan-panel">
                        <div class="panel-head">
                            <h3>Savings goals</h3>
                            <span>Auto transfers scheduled</span>
                        </div>
                        <div class="goals-list">
                            <div class="goal">
                                <div class="goal-info">
                                    <h4>Emergency fund</h4>
                                    <p>PKR62,000 / 90,000</p>
                                </div>
                                <span class="pill">69%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill green" style="width:69%;"></div>
                            </div>
                            <div class="goal">
                                <div class="goal-info">
                                    <h4>Travel (Siargao)</h4>
                                    <p>PKR18,500 / 35,000</p>
                                </div>
                                <span class="pill" style="background:rgba(249,115,22,0.15); color:var(--accent);">53%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:53%; background:linear-gradient(120deg,#f97316,#fb923c);"></div>
                            </div>
                            <div class="goal">
                                <div class="goal-info">
                                    <h4>Investment top-up</h4>
                                    <p>PKR9,200 / 12,000</p>
                                </div>
                                <span class="pill" style="background:rgba(14,165,233,0.15); color:#0ea5e9;">77%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:77%; background:linear-gradient(120deg,#0ea5e9,#38bdf8);"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="plan-grid">
                    <div class="plan-panel">
                        <div class="panel-head">
                            <h3>Upcoming payments</h3>
                            <span>Next 10 days</span>
                        </div>
                        <div class="schedule-list">
                            <div class="schedule-row">
                                <div>
                                    <strong>Condo rent</strong>
                                    <p style="margin:4px 0 0; color:var(--muted);">Due Nov 28 · Auto transfer</p>
                                </div>
                                <span class="amount">PKR12,500</span>
                            </div>
                            <div class="schedule-row">
                                <div>
                                    <strong>Car loan</strong>
                                    <p style="margin:4px 0 0; color:var(--muted);">Due Nov 25</p>
                                </div>
                                <span class="amount">PKR5,800</span>
                            </div>
                            <div class="schedule-row">
                                <div>
                                    <strong>Gym & wellness</strong>
                                    <p style="margin:4px 0 0; color:var(--muted);">Due Nov 22</p>
                                </div>
                                <span class="amount">PKR1,200</span>
                            </div>
                        </div>
                    </div>
                    <div class="plan-panel">
                        <div class="panel-head">
                            <h3>Recent activity</h3>
                            <span>Logged today</span>
                        </div>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h4>Added salary scenario · 13th month</h4>
                                    <p>Projected bonus of PKR15,000 tagged for investments</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot" style="background:#10b981;"></div>
                                <div class="timeline-content">
                                    <h4>Updated grocery envelope</h4>
                                    <p>+PKR1,200 after weekend shopping</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot" style="background:#f97316;"></div>
                                <div class="timeline-content">
                                    <h4>Goal milestone unlocked</h4>
                                    <p>Emergency fund reached 70% completion</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="footer">
                © {{ date('Y') }} Salary Planner · Plan confidently, spend intentionally.
            </footer>
        </div>
    </body>
</html>

