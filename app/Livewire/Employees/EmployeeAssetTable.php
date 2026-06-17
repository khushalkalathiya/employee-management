<?php

namespace App\Livewire\Employees;

use App\Livewire\BaseTable;
use App\Models\Asset;
use App\Models\Employee;

class EmployeeAssetTable extends BaseTable
{
    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function render()
    {
        $assets = Asset::where('employee_id', $this->employee->id)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('asset_name', 'like', "%{$this->search}%")
                        ->orWhere('asset_type', 'like', "%{$this->search}%")
                        ->orWhere('serial_number', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.employees.employee-asset-table', [
            'employee_id' => $this->employee->id,
            'assets' => $assets,
        ]);
    }
}
