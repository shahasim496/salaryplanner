<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="planner-shell">
        <x-planner-sidebar active="loans" subtitle="Loans management" />

        <main class="planner-main">
            <section class="planner-hero">
                <div style="max-width:520px;">
                    <span class="planner-pill">{{ $loan->loan_type === 'owed_to_me' ? 'Owed to Me' : 'I Owe' }} · {{ $loan->person_name }}</span>
                    <h1>Loan Details</h1>
                    <p>Track all loans and payments for {{ $loan->person_name }}.</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span>Total Loaned</span>
                            <strong>PKR{{ number_format($loan->total_loaned, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Total Paid</span>
                            <strong style="color:#16a34a;">PKR{{ number_format($loan->total_paid, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Remaining</span>
                            <strong style="color:{{ $loan->remaining_amount > 0 ? '#dc2626' : '#16a34a' }};">PKR{{ number_format($loan->remaining_amount, 2) }}</strong>
                        </div>
                        <div class="stat-card">
                            <span>Status</span>
                            <strong>
                                <span class="badge {{ $loan->status === 'Paid' ? 'badge-success' : ($loan->status === 'Partial' ? 'badge-warning' : 'badge-info') }}">
                                    {{ $loan->status }}
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
                        <button onclick="document.getElementById('addEntryModal').classList.add('active')" class="primary-btn" style="text-align:center;">Add Loan Entry</button>
                        <button onclick="document.getElementById('addPaymentModal').classList.add('active')" class="primary-btn" style="text-align:center; background:linear-gradient(120deg, #16a34a, #22c55e);">Add Payment</button>
                        <a href="{{ route('loans.edit', $loan->id) }}" class="ghost-btn" style="text-align:center;">Edit Details</a>
                        <form method="POST" action="{{ route('loans.destroy', $loan->id) }}" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ghost-btn" style="width:100%; text-align:center; color:#dc2626;" onclick="return confirm('Are you sure you want to delete this loan record?')">Delete</button>
                        </form>
                        <a href="{{ route('loans.index') }}" class="ghost-btn" style="text-align:center;">Back to Loans</a>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="planner-panel" style="border-left:4px solid #22c55e; background:#ecfdf5;">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Loan Entries Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(59,130,246,0.1); color:#2563eb;">Loan Entries</p>
                        <h3>All Loans</h3>
                        <span>Individual loan amounts given/taken</span>
                    </div>
                    <button onclick="document.getElementById('addEntryModal').classList.add('active')" class="primary-btn">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Entry
                    </button>
                </div>
                @if($loan->loanEntries->count())
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->loanEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                                        <td style="font-weight:600;">PKR{{ number_format($entry->amount, 2) }}</td>
                                        <td>{{ $entry->description ?? '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('loans.delete-entry', [$loan->id, $entry->id]) }}" style="display:inline;">
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
                    <p style="text-align:center; padding:24px; color:var(--muted);">No loan entries yet. Click "Add Entry" to add one.</p>
                @endif
            </section>

            <!-- Payments Section -->
            <section class="planner-panel">
                <div class="section-header">
                    <div>
                        <p class="planner-pill" style="background:rgba(34,197,94,0.1); color:#16a34a;">Payments</p>
                        <h3>Payment History</h3>
                        <span>All payments made back</span>
                    </div>
                    <button onclick="document.getElementById('addPaymentModal').classList.add('active')" class="primary-btn" style="background:linear-gradient(120deg, #16a34a, #22c55e);">
                        <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Payment
                    </button>
                </div>
                @if($loan->loanPayments->count())
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->loanPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td style="font-weight:600; color:#16a34a;">PKR{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->description ?? '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('loans.delete-payment', [$loan->id, $payment->id]) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Delete this payment?')">
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
                    <p style="text-align:center; padding:24px; color:var(--muted);">No payments recorded yet. Click "Add Payment" to record one.</p>
                @endif
            </section>

            @if($loan->notes)
            <section class="planner-panel">
                <div class="panel-head">
                    <div>
                        <p class="planner-pill" style="background:rgba(100,116,139,0.1); color:var(--muted);">Notes</p>
                        <h2>Additional Notes</h2>
                    </div>
                </div>
                <p style="color:var(--text-dark);">{{ $loan->notes }}</p>
            </section>
            @endif
        </main>
    </div>

    <!-- Add Entry Modal -->
    <div id="addEntryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Loan Entry</h3>
                <button onclick="document.getElementById('addEntryModal').classList.remove('active')" class="modal-close">&times;</button>
            </div>
            <form method="POST" action="{{ route('loans.add-entry', $loan->id) }}">
                @csrf
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
                    <textarea id="entry_description" name="description" class="planner-textarea" placeholder="What is this loan for?"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="document.getElementById('addEntryModal').classList.remove('active')" class="ghost-btn">Cancel</button>
                    <button type="submit" class="primary-btn">Add Entry</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div id="addPaymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Payment</h3>
                <button onclick="document.getElementById('addPaymentModal').classList.remove('active')" class="modal-close">&times;</button>
            </div>
            <form method="POST" action="{{ route('loans.add-payment', $loan->id) }}">
                @csrf
                <div class="form-field">
                    <label for="payment_amount">Amount (PKR)</label>
                    <input id="payment_amount" type="number" step="0.01" min="0.01" name="amount" class="planner-input" required>
                </div>
                <div class="form-field">
                    <label for="payment_date">Payment Date</label>
                    <input id="payment_date" type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="planner-input" required>
                </div>
                <div class="form-field">
                    <label for="payment_description">Description (Optional)</label>
                    <textarea id="payment_description" name="description" class="planner-textarea" placeholder="Payment notes"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="document.getElementById('addPaymentModal').classList.remove('active')" class="ghost-btn">Cancel</button>
                    <button type="submit" class="primary-btn" style="background:linear-gradient(120deg, #16a34a, #22c55e);">Record Payment</button>
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
