<?php

namespace App\Livewire\Departments;

use App\Livewire\BaseTable;
use App\Models\Department;

class DepartmentTable extends BaseTable
{
    public function render()
    {
        $departments = Department::query()
            ->withCount('designations')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.departments.department-table', [
            'departments' => $departments,
        ]);
    }
}
