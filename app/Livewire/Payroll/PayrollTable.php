<?php

namespace App\Livewire\Payroll;

use App\Livewire\BaseTable;
use App\Models\Salary;

class PayrollTable extends BaseTable
{
    public string $statusFilter = '';
    public string $monthFilter  = '';

    public function render()
    {
        $query = Salary::query()
            ->with(['employee.user', 'employee.department'])
            ->when(! has_permission('payroll.view') && has_permission('payroll.view.own'), function ($q) {
                // Restrict to own employee record
                $employeeId = auth_user()->employee?->id;
                $q->where('employee_id', $employeeId);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('employee.user', function ($u) {
                    $u->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$this->search}%"]);
                });
            })
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->monthFilter, function ($q) {
                $q->whereYear('salary_month', substr($this->monthFilter, 0, 4))
                  ->whereMonth('salary_month', substr($this->monthFilter, 5, 2));
            });

        $payrolls = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.payroll.payroll-table', compact('payrolls'));
    }
}
