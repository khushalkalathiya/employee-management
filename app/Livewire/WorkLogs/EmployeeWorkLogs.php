<?php

namespace App\Livewire\WorkLogs;

use App\Livewire\BaseTable;
use App\Models\EmployeeWork;
use Livewire\Attributes\On;

class EmployeeWorkLogs extends BaseTable
{
    #[On('refresh-table')]
    public function refreshTable(): void
    {
        // Simply triggers a component re-render
    }

    public function render()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $hasOwn = $user->can('Work Logs Own') || $user->can('work_log.own');

        $works = EmployeeWork::query()
            ->with(['employee', 'employee.user'])
            ->when($hasOwn, function($q) use ($employee) {
                $q->where('employee_id', $employee?->id ?? 0);
            })
            ->when($this->search, function($q) {
                $q->where(function($q2) {
                    $q2->where('project_title', 'like', '%' . $this->search . '%')
                       ->orWhere('description', 'like', '%' . $this->search . '%')
                       ->orWhereHas('employee.user', function($q3) {
                           $q3->where('first_name', 'like', '%' . $this->search . '%')
                              ->orWhere('last_name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                       });
                });
            })
            ->orderBy('date', 'desc')
            ->paginate($this->perPage);

        return view('livewire.work-logs.employee-work-logs', [
            'works' => $works,
            'isOwnOnly' => $hasOwn
        ]);
    }
}
