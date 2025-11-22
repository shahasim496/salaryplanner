<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="investments" subtitle="Investments" />

        <main class="planner-main">
            <section class="planner-hero">
                <div style="max-width:520px;">
                    <span class="planner-pill">Investment · {{ $investment->investment_name }}</span>
                    <h1>Investment Details</h1>
                    <p>Track all investment amounts, profits, and losses for {{ $investment->investment_name }}.</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span>Total Invested</span>
                            <strong>PKR{{ number_format($investment->total_invested, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Withdrawn</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($investment->total_withdrawn, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Profit</span>
                            <strong style="color:#16a34a;">PKR{{ number_format($investment->total_profit, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Loss</span>
                            <strong style="color:#dc2626;">PKR{{ number_format($investment->total_loss, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Current Value / Remaining</span>
                            <strong style="color:{{ $investment->current_value >= ($investment->total_invested - $investment->total_withdrawn) ? '#16a34a' : '#dc2626' }};">PKR{{ number_format($investment->current_value, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Status</span>
                            <strong>
                                <span class="badge {{ $investment->status === 'Active' ? 'badge-success' : 'badge-gray' }}">
                                    {{ $investment->status }}
                                </span>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="planner-panel" style="flex:0 0 280px;">
                    <div class="panel-head">
                        <span style="font-size:0.8rem; letter-spacing:0.14em; color:var(--muted); text-transform:uppercase;">Quick actions</span>
                        <h2 style="font-size:1.2rem; margin:4px 0 14px;">Manage</h2>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <button onclick="document.getElementById('addEntryModal').classList.add('active')" class="primary-btn" style="text-align:center;">Add Investment</button>
                        <button onclick="document.getElementById('addProfitModal').classList.add('active')" class="primary-btn" style="text-align:center; background:linear-gradient(120deg, #16a34a, #22c55e);">Add Profit/Loss</button>
                        <a href="{{ route('investments.edit', $investment->id) }}" class="ghost-btn" style="text-align:center;">Edit Details</a>
                        <form method="POST" action="{{ route('investments.destroy', $investment->id) }}" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ghost-btn" style="width:100%; text-align:center; color:#dc2626;" onclick="return confirm('Are you sure you want to delete this investment?')">Delete</button>
                        </form>
                        <a href="{{ route('investments.index') }}" class="ghost-btn" style="text-align:center;">Back to Investments</a>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Investment Entries Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(59,130,246,0.1); color:#2563eb;">Investment Entries</p>
                        <h3>Investment Amounts</h3>
                        <span>Increase or decrease your investment amount</span>
                    </div>
                    <button onclick="document.getElementById('addEntryModal').classList.add('active')" class="primary-btn">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Entry
                    </button>
                </div>
                @if($investment->investmentEntries->count())
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($investment->investmentEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge {{ $entry->entry_type === 'increase' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $entry->entry_type === 'increase' ? 'Increase' : 'Decrease' }}
                                            </span>
                                        </td>
                                        <td style="font-weight:600; color:{{ $entry->entry_type === 'increase' ? '#16a34a' : '#dc2626' }};">{{ $entry->entry_type === 'increase' ? '+' : '-' }}PKR{{ number_format($entry->amount, 2) }}</td>
                                        <td>{{ $entry->description ?? '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('investments.delete-entry', [$investment->id, $entry->id]) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this entry?')">
                                                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align:center; padding:24px; color:var(--muted);">No investment entries yet. Click "Add Entry" to add one.</p>
                @endif
            </section>

            <!-- Profit/Loss Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(34,197,94,0.1); color:#16a34a;">Profit & Loss</p>
                        <h3>Monthly Profit/Loss Records</h3>
                        <span>Track profit and loss for each month</span>
                    </div>
                    <button onclick="document.getElementById('addProfitModal').classList.add('active')" class="primary-btn" style="background:linear-gradient(120deg, #16a34a, #22c55e);">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Record
                    </button>
                </div>
                @if($investment->investmentProfits->count())
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Profit</th>
                                    <th>Loss</th>
                                    <th>Net</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($investment->investmentProfits as $profit)
                                    @php
                                        $net = $profit->profit_amount - $profit->loss_amount;
                                    @endphp
                                    <tr>
                                        <td style="font-weight:600;">{{ \Carbon\Carbon::parse($profit->month . '-01')->format('F Y') }}</td>
                                        <td style="color:#16a34a; font-weight:600;">PKR{{ number_format($profit->profit_amount, 2) }}</td>
                                        <td style="color:#dc2626; font-weight:600;">PKR{{ number_format($profit->loss_amount, 2) }}</td>
                                        <td style="font-weight:600; color:{{ $net >= 0 ? '#16a34a' : '#dc2626' }};">{{ $net >= 0 ? '+' : '' }}PKR{{ number_format($net, 2) }}</td>
                                        <td>{{ $profit->description ?? '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('investments.delete-profit', [$investment->id, $profit->id]) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this profit/loss record?')">
                                                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align:center; padding:24px; color:var(--muted);">No profit/loss records yet. Click "Add Record" to add one.</p>
                @endif
            </section>

            @if($investment->description || $investment->notes)
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(100,116,139,0.1); color:var(--muted);">Details</p>
                        <h2>Additional Information</h2>
                    </div>
                </div>
                @if($investment->description)
                    <div style="margin-bottom:16px;">
                        <strong style="color:var(--text-dark);">Description:</strong>
                        <p style="color:var(--text-dark); margin-top:4px;">{{ $investment->description }}</p>
                    </div>
                @endif
                @if($investment->notes)
                    <div>
                        <strong style="color:var(--text-dark);">Notes:</strong>
                        <p style="color:var(--text-dark); margin-top:4px;">{{ $investment->notes }}</p>
                    </div>
                @endif
            </section>
            @endif
        </main>
    </div>

    <!-- Add Entry Modal -->
    <div id="addEntryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Investment Entry</h3>
                <button onclick="document.getElementById('addEntryModal').classList.remove('active')" class="modal-close">&times;</button>
            </div>
            <form method="POST" action="{{ route('investments.add-entry', $investment->id) }}">
                @csrf
                <div class="form-field">
                    <label for="entry_type">Type</label>
                    <select id="entry_type" name="entry_type" class="planner-select" required>
                        <option value="increase">Increase (Add Money)</option>
                        <option value="decrease">Decrease (Withdraw Money)</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="entry_amount">Amount (PKR)</label>
                    <input id="entry_amount" type="number" step="0.01" min="0.01" name="amount" class="planner-input" required>
                </div>
                <div class="form-field">
                    <label for="entry_date">Entry Date</label>
                    <input id="entry_date" type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="planner-input" required>
                </div>
                <div class="form-field">
                    <label for="entry_description">Description (Optional)</label>
                    <textarea id="entry_description" name="description" class="planner-textarea" placeholder="What is this for?"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="document.getElementById('addEntryModal').classList.remove('active')" class="ghost-btn">Cancel</button>
                    <button type="submit" class="primary-btn">Add Entry</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Profit/Loss Modal -->
    <div id="addProfitModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Profit/Loss Record</h3>
                <button onclick="document.getElementById('addProfitModal').classList.remove('active')" class="modal-close">&times;</button>
            </div>
            <form method="POST" action="{{ route('investments.add-profit', $investment->id) }}">
                @csrf
                <div class="form-field">
                    <label for="profit_month">Month</label>
                    <input id="profit_month" type="month" name="month" value="{{ date('Y-m') }}" class="planner-input" required>
                </div>
                <div class="form-field">
                    <label for="profit_amount">Profit Amount (PKR)</label>
                    <input id="profit_amount" type="number" step="0.01" min="0" name="profit_amount" value="0" class="planner-input">
                </div>
                <div class="form-field">
                    <label for="loss_amount">Loss Amount (PKR)</label>
                    <input id="loss_amount" type="number" step="0.01" min="0" name="loss_amount" value="0" class="planner-input">
                </div>
                <div class="form-field">
                    <label for="profit_description">Description (Optional)</label>
                    <textarea id="profit_description" name="description" class="planner-textarea" placeholder="Notes about this month's performance"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="document.getElementById('addProfitModal').classList.remove('active')" class="ghost-btn">Cancel</button>
                    <button type="submit" class="primary-btn" style="background:linear-gradient(120deg, #16a34a, #22c55e);">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>
</x-app-layout>

