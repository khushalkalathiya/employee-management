<?php

namespace App\Livewire\Employees;

use App\Livewire\BaseTable;
use App\Models\User;

class EmployeeTable extends BaseTable
{
    public function render()
    {
        $employees = User::query()
            ->with('roles')
            ->where('id', '!=', authId())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhereRaw(
                            "CONCAT(first_name, ' ', last_name) LIKE ?",
                            ["%{$this->search}%"]
                        )
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.employees.employee-table', [
            'employees' => $employees,
        ]);
    }
}