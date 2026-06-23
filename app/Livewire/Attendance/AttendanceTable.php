<?php

namespace App\Livewire\Attendance;

use Livewire\Component;

/**
 * Shell component that owns the Control Card and delegates the
 * lower panel to EmployeeAttendance (attendance.own) or
 * AdminAttendance (admin/manager view).
 */
class AttendanceTable extends Component
{
    public function render()
    {
        return view('livewire.attendance.attendance-table');
    }
}
