<?php

namespace App\Livewire\Employees;

use App\Livewire\BaseTable;
use App\Models\Employee;
use App\Models\Experience;

class EmployeeExperienceTable extends BaseTable
{
    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function render()
    {
        $experiences = Experience::where('employee_id', $this->employee->id)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('company_name', 'like', "%{$this->search}%")
                        ->orWhere('designation', 'like', "%{$this->search}%")
                        ->orWhere('location', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.employees.employee-experience-table', [
            'employee_id' => $this->employee->id,
            'experiences' => $experiences,
        ]);
    }
}
