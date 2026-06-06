<?php

namespace App\Livewire\Designations;

use App\Livewire\BaseTable;
use App\Models\Designation;

class DesignationTable extends BaseTable
{
    public string $sortField = 'created_at';

    public function render()
    {
        $designations = Designation::query()
            ->select('designations.*')
            ->with('department')
            ->leftJoin('departments', 'departments.id', '=', 'designations.department_id')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('designations.name', 'like', "%{$this->search}%")
                        ->orWhere('departments.name', 'like', "%{$this->search}%");
                });
            })
            ->when(
                $this->sortField === 'department_name',
                fn ($query) => $query->orderBy('departments.name', $this->sortDirection),
                fn ($query) => $query->orderBy('designations.' . $this->sortField, $this->sortDirection)
            )
            ->paginate($this->perPage);

        return view('livewire.designations.designation-table', [
            'designations' => $designations,
        ]);
    }
}
