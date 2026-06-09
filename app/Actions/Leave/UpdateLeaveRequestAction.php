<?php

namespace App\Actions\Leave;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateLeaveRequestAction
{
    public function handle(LeaveRequest $leave, array $data): LeaveRequest
    {
        return DB::transaction(function () use ($leave, $data) {

            switch ($data['leave_mode']) {

                case 'full_day':

                    $date = Carbon::parse($data['leave_date']);
                    $data['start_datetime'] = $date->copy()->startOfDay();
                    $data['end_datetime'] = $date->copy()->endOfDay();
                    $data['total_days'] = 1;
                    $data['total_hours'] = null;

                    break;

                case 'multiple_days':

                    $start = Carbon::parse($data['start_datetime']);
                    $end = Carbon::parse($data['end_datetime']);
                    $data['start_datetime'] = $start;
                    $data['end_datetime'] = $end;
                    $data['total_days'] = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1;
                    $data['total_hours'] = null;

                    break;

                case 'half_day':

                    $date = Carbon::parse($data['leave_date']);
                    $start = Carbon::createFromFormat('g:i A', $data['start_time']);
                    $end = Carbon::createFromFormat('g:i A', $data['end_time']);
                    $data['start_datetime'] = $date->copy()->setTime($start->hour, $start->minute);
                    $data['end_datetime'] = $date->copy()->setTime($end->hour, $end->minute);
                    $data['total_days'] = 0.5;
                    $data['total_hours'] = $start->diffInMinutes($end) / 60;

                    break;
            }

            $leave->update([
                'user_id' => $data['user_id'] ?? $leave->user_id,
                'leave_type_id' => $data['leave_type_id'],
                'leave_mode' => $data['leave_mode'],
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'total_days' => $data['total_days'],
                'total_hours' => $data['total_hours'],
                'reason' => $data['reason'] ?? '',
            ]);

            return $leave->fresh([
                'user',
                'leaveType',
                'approver',
            ]);
        });
    }
}