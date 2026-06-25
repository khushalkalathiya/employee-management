<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_month',
        'start_date',
        'end_date',
        'is_hourly',
        'total_hours',
        'hourly_rate',
        'working_days',
        'present_days',
        'leave_days',
        'per_day_salary',
        'earned_salary',
        'pf_amount',
        'other_deductions',
        'hold_amount',
        'final_salary',
        'notes',
        'status',
        'processed_by',
        'paid_at',
    ];

    protected $casts = [
        'salary_month'     => 'date',
        'start_date'       => 'date',
        'end_date'         => 'date',
        'paid_at'          => 'datetime',
        'is_hourly'        => 'boolean',
        'total_hours'      => 'decimal:2',
        'hourly_rate'      => 'decimal:4',
        'per_day_salary'   => 'decimal:4',
        'earned_salary'    => 'decimal:4',
        'pf_amount'        => 'decimal:4',
        'other_deductions' => 'decimal:4',
        'hold_amount'      => 'decimal:4',
        'final_salary'     => 'decimal:4',
        'present_days'     => 'decimal:2',
        'leave_days'       => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'paid'      => 'pill-green',
            'pending'   => 'pill-amber',
            'cancelled' => 'pill-red',
            default     => 'pill-gray',
        };
    }

    public function getPeriodLabelAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            return dateFormat($this->start_date) . ' – ' . dateFormat($this->end_date);
        }

        return $this->salary_month ? $this->salary_month->format('F Y') : '—';
    }
}
