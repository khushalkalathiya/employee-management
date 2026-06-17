<?php

namespace App\Livewire\Employees;

use App\Livewire\BaseTable;
use App\Models\Education;
use App\Models\Employee;

class EmployeeEducationTable extends BaseTable
{
    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function render()
    {
        $educationList = Education::where('employee_id', $this->employee->id)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('qualification', 'like', "%{$this->search}%")
                        ->orWhere('institute_name', 'like', "%{$this->search}%")
                        ->orWhere('board_university', 'like', "%{$this->search}%")
                        ->orWhere('passing_year', 'like', "%{$this->search}%")
                        ->orWhere('percentage_grade', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.employees.employee-education-table', [
            'employee_id' => $this->employee->id,
            'educationList' => $educationList,
        ]);
    }
}
