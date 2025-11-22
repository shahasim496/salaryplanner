<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentProfit extends Model
{
    protected $fillable = [
        'investment_id',
        'month',
        'profit_amount',
        'loss_amount',
        'description',
        'created_by',
    ];

    protected $casts = [
        'profit_amount' => 'decimal:2',
        'loss_amount' => 'decimal:2',
    ];

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
