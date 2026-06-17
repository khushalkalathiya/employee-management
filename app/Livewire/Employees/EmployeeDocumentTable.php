<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Livewire\BaseTable;
use App\Models\Employee;
use App\Models\Document;

class EmployeeDocumentTable extends BaseTable
{
    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function render()
    {
        $documents = Document::with('media')
            ->where('employee_id', $this->employee->id)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('document_type', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.employees.employee-document-table', [
            'employee_id' => $this->employee->id,
            'documents' => $documents,
        ]);
    }
}