<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saving extends Model
{
    protected $fillable = [
        'salary_plan_id',
        'saving_name',
        'saving_type',
        'planned_amount',
        'actual_amount',
        'accumulated_amount',
        'description',
        'target_goal',
        'target_amount',
        'target_date',
        'is_achieved',
        'priority',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'accumulated_amount' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'target_date' => 'date',
        'is_achieved' => 'boolean',
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
