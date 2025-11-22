<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'person_name',
        'loan_type',
        'total_loaned',
        'total_paid',
        'remaining_amount',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_loaned' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loanEntries(): HasMany
    {
        return $this->hasMany(LoanEntry::class);
    }

    public function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function recalculateTotals(): void
    {
        $this->total_loaned = $this->loanEntries()->sum('amount');
        $this->total_paid = $this->loanPayments()->sum('amount');
        $this->remaining_amount = $this->total_loaned - $this->total_paid;
        
        if ($this->remaining_amount <= 0) {
            $this->status = 'Paid';
        } elseif ($this->total_paid > 0) {
            $this->status = 'Partial';
        } else {
            $this->status = 'Active';
        }
        
        $this->save();
    }
}
