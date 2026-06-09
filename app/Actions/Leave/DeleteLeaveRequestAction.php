<?php

namespace App\Actions\Leave;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;

class DeleteLeaveRequestAction
{
    /**
     * Soft delete a leave request.
     */
    public function handle(LeaveRequest $leave): void
    {
        DB::transaction(function () use ($leave) {
            $leave->delete();
        });
    }
}
?>
