<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'salary_plan_id',
        'expense_name',
        'category',
        'planned_amount',
        'actual_amount',
        'due_date',
        'paid_date',
        'description',
        'is_paid',
        'is_recurring',
        'priority',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'is_paid' => 'boolean',
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
