<?php

namespace App\Actions\LeaveType;

use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLeaveTypeAction
{
    use AsAction;

    public function handle(LeaveType $leaveType): bool
    {
        return DB::transaction(fn () => $leaveType->delete());
    }
}
