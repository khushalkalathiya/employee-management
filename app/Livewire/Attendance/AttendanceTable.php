<?php

namespace App\Livewire\Attendance;

use App\Livewire\BaseTable;

class AttendanceTable extends BaseTable
{
    public function render()
    {
        return view('livewire.attendance.attendance-table', []);
    }
}
