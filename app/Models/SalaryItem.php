<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryItem extends Model
{
    protected $fillable = [
        'salary_plan_id',
        'item_name',
        'item_type',
        'category',
        'amount',
        'description',
        'is_recurring',
        'priority',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'priority' => 'integer',
    ];

    public function salaryPlan(): BelongsTo
    {
        return $this->belongsTo(SalaryPlan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
