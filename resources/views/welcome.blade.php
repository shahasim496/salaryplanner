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
        <style>
            :root {
                --page-bg: #edf2ff;
                --panel-bg: #ffffff;
                --panel-border: #e2e8f0;
                --text-dark: #0f172a;
                --muted: #64748b;
                --primary: #2563eb;
                --accent: #f97316;
                --shadow: 0 30px 70px rgba(15, 23, 42, 0.12);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Inter', sans-serif;
                background: radial-gradient(circle at top, #eff6ff 0%, #e0e7ff 40%, #eef2ff 100%);
                color: var(--text-dark);
                min-height: 100vh;
            }

            a {
                text-decoration: none;
            }

            .page-shell {
                max-width: 1200px;
                margin: 0 auto;
                padding: 32px 24px 72px;
            }

            .top-nav {
                background: rgba(255, 255, 255, 0.9);
                border-radius: 28px;
                padding: 18px 26px;
                border: 1px solid rgba(226, 232, 240, 0.8);
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                backdrop-filter: blur(6px);
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .brand-mark {
                width: 54px;
                height: 54px;
                border-radius: 18px;
                background: linear-gradient(135deg, #2563eb, #7c3aed);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                letter-spacing: 0.04em;
            }

            .brand h1 {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 700;
            }

            .nav-actions {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }

            .primary-btn,
            .ghost-btn {
                border-radius: 14px;
                font-weight: 600;
                padding: 12px 18px;
                font-size: 0.95rem;
                transition: 0.2s ease;
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 120px;
            }

            .primary-btn {
                background: linear-gradient(120deg, #2563eb, #7c3aed);
                color: #fff;
                box-shadow: 0 20px 35px rgba(99, 102, 241, 0.3);
            }

            .ghost-btn {
                background: transparent;
                color: var(--primary);
                border: 1px solid rgba(37, 99, 235, 0.2);
            }

            .primary-btn:hover {
                transform: translateY(-2px);
            }

            .ghost-btn:hover {
                border-color: rgba(37, 99, 235, 0.4);
            }

            .content {
                margin-top: 32px;
                display: flex;
                flex-direction: column;
                gap: 28px;
            }

            .hero {
                background: #fff;
                border-radius: 36px;
                padding: 46px;
                border: 1px solid var(--panel-border);
                box-shadow: var(--shadow);
                display: grid;
                grid-template-columns: 1.15fr 0.85fr;
                gap: 32px;
                align-items: center;
            }

            .hero h2 {
                margin: 0 0 16px;
                font-size: 2.6rem;
                line-height: 1.1;
            }

            .hero p {
                margin: 0 0 24px;
                font-size: 1.05rem;
                color: var(--muted);
                max-width: 540px;
            }

            .cta-buttons {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }

            .hero-summary {
                background: linear-gradient(145deg, #1d4ed8, #0f172a);
                color: #fff;
                border-radius: 30px;
                padding: 32px;
                box-shadow: 0 30px 60px rgba(15, 23, 42, 0.4);
            }

            .hero-summary h3 {
                margin: 0 0 10px;
                font-size: 1.15rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                opacity: 0.8;
            }

            .hero-summary .payday {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 18px;
            }

            .snapshot-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                font-weight: 600;
            }

            .snapshot-row span:last-child {
                opacity: 0.85;
            }

            .progress-track {
                width: 100%;
                height: 12px;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 999px;
                overflow: hidden;
                margin-bottom: 26px;
            }

            .progress-fill {
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(120deg, #38bdf8, #a78bfa);
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
                gap: 18px;
            }

            .stat-card {
                background: #fff;
                border-radius: 26px;
                padding: 22px;
                border: 1px solid var(--panel-border);
                box-shadow: var(--shadow);
            }

            .stat-label {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--muted);
            }

            .stat-value {
                font-size: 2rem;
                font-weight: 700;
                margin: 12px 0;
            }

            .stat-hint {
                font-size: 0.85rem;
                color: var(--muted);
            }

            .plan-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 24px;
            }

            .plan-panel {
                background: #fff;
                border-radius: 30px;
                padding: 28px;
                border: 1px solid var(--panel-border);
                box-shadow: var(--shadow);
                display: flex;
                flex-direction: column;
                gap: 18px;
                min-height: 280px;
            }

            .panel-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
            }

            .panel-head h3 {
                margin: 0;
                font-size: 1.2rem;
            }

            .panel-head span {
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--muted);
            }

            .bar-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .bar-item {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .bar-item label {
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                font-size: 0.9rem;
            }

            .bar-track {
                width: 100%;
                height: 12px;
                border-radius: 999px;
                background: #eef2ff;
                overflow: hidden;
            }

            .bar-fill {
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(120deg, #38bdf8, #0ea5e9);
            }

            .bar-fill.green {
                background: linear-gradient(120deg, #34d399, #10b981);
            }

            .goals-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .goal {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
            }

            .goal-info h4 {
                margin: 0 0 4px;
            }

            .goal-info p {
                margin: 0;
                color: var(--muted);
                font-size: 0.85rem;
            }

            .goal img {
                display: none;
            }

            .pill {
                padding: 6px 12px;
                border-radius: 999px;
                background: rgba(37, 99, 235, 0.08);
                color: var(--primary);
                font-weight: 600;
                font-size: 0.8rem;
            }

            .schedule-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .schedule-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 14px;
                padding-bottom: 14px;
                border-bottom: 1px dashed var(--panel-border);
            }

            .schedule-row:last-child {
                border-bottom: none;
            }

            .amount {
                font-weight: 700;
            }

            .timeline {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .timeline-item {
                display: flex;
                gap: 14px;
                align-items: flex-start;
            }

            .timeline-dot {
                width: 14px;
                height: 14px;
                border-radius: 50%;
                background: #2563eb;
                margin-top: 5px;
            }

            .timeline-content h4 {
                margin: 0;
                font-size: 0.95rem;
            }

            .timeline-content p {
                margin: 4px 0 0;
                color: var(--muted);
                font-size: 0.85rem;
            }

            .footer {
                text-align: center;
                margin-top: 28px;
                color: var(--muted);
                font-size: 0.85rem;
            }

            @media (max-width: 900px) {
                .hero {
                    grid-template-columns: 1fr;
                    padding: 32px;
                }
            }

            @media (max-width: 600px) {
                .top-nav {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .hero {
                    padding: 26px;
                }

                .cta-buttons {
                    flex-direction: column;
                }
            }
        </style>
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
                        <a href="{{ route('register') }}" class="primary-btn">Create free plan</a>
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
                                <a href="{{ route('register') }}" class="primary-btn">Start a plan</a>
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

