<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySpending extends Model
{
    protected $fillable = [
        'salary_plan_id',
        'category',
        'amount',
        'spending_date',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spending_date' => 'date',
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
