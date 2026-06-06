<?php

namespace App\Livewire\Roles;

use App\Livewire\BaseTable;
use App\Models\Role;

class RoleTable extends BaseTable
{
    public function render()
    {
        $roles = Role::query()
            ->withCount('permissions')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('display_name', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.roles.role-table', [
            'roles' => $roles,
        ]);
    }
}
