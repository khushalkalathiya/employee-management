<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'leave_duration',
        'from_date',
        'to_date',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

}
