<?php

namespace App\Livewire\Leave;

use App\Livewire\BaseTable;
use App\Models\LeaveRequest;

class LeaveRequestTable extends BaseTable
{
    public function render()
    {
        $leaveRequests = LeaveRequest::query()
            ->with(['user', 'leaveType', 'approver'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('user', function ($userQuery) {
                        $userQuery->where('first_name', 'like', "%{$this->search}%")
                            ->orWhere('last_name', 'like', "%{$this->search}%")
                            ->orWhereRaw(
                                "CONCAT(first_name, ' ', last_name) LIKE ?",
                                ["%{$this->search}%"]
                            )
                            ->orWhere('email', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('leaveType', function ($leaveTypeQuery) {
                        $leaveTypeQuery->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhere('reason', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.leave.leave-request-table', [
            'leaveRequests' => $leaveRequests,
        ]);
    }
}
