<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'display_name',
        'guard_name',
    ];

    public function getLabelAttribute(): string
    {
        return $this->display_name ?: ucfirst($this->name);
    }
}