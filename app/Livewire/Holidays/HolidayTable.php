<?php

namespace App\Livewire\Holidays;

use App\Livewire\BaseTable;
use App\Models\Holiday;

class HolidayTable extends BaseTable
{
    public function render()
    {
        $holidays = Holiday::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.holidays.holiday-table', [
            'holidays' => $holidays,
        ]);
    }
}
