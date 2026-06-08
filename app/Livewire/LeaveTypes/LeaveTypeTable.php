<?php

namespace App\Livewire\LeaveTypes;

use App\Livewire\BaseTable;
use App\Models\LeaveType;

class LeaveTypeTable extends BaseTable
{
    public function render()
    {
        $leaveTypes = LeaveType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.leave-types.leave-type-table', [
            'leaveTypes' => $leaveTypes,
        ]);
    }
}
