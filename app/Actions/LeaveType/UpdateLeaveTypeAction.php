<?php

namespace App\Actions\LeaveType;

use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLeaveTypeAction
{
    use AsAction;

    public function handle(LeaveType $leaveType, array $data): LeaveType
    {
        return DB::transaction(function () use ($leaveType, $data) {
            $leaveType->update([
                'name' => $data['name'],
                'monthly_limit' => $data['monthly_limit'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            return $leaveType->fresh();
        });
    }
}
