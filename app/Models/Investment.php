<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'investment_name',
        'description',
        'total_invested',
        'total_withdrawn',
        'total_profit',
        'total_loss',
        'current_value',
        'remaining_amount',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_invested' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'total_loss' => 'decimal:2',
        'current_value' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function investmentEntries(): HasMany
    {
        return $this->hasMany(InvestmentEntry::class);
    }

    public function investmentProfits(): HasMany
    {
        return $this->hasMany(InvestmentProfit::class);
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
        // Calculate total invested (sum of all increases)
        $totalIncrease = $this->investmentEntries()->where('entry_type', 'increase')->sum('amount');
        $totalDecrease = $this->investmentEntries()->where('entry_type', 'decrease')->sum('amount');
        $this->total_invested = $totalIncrease;
        $this->total_withdrawn = $totalDecrease;

        // Calculate total profit and loss
        $this->total_profit = $this->investmentProfits()->sum('profit_amount');
        $this->total_loss = $this->investmentProfits()->sum('loss_amount');

        // Current value = (invested - withdrawn) + profit - loss
        $netInvested = $totalIncrease - $totalDecrease;
        $this->current_value = $netInvested + $this->total_profit - $this->total_loss;
        
        // Remaining amount is the same as current value
        $this->remaining_amount = $this->current_value;

        $this->save();
    }
}
