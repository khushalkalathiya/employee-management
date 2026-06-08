<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'name',
        'start',
        'end',
        'notes',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end'   => 'datetime',
    ];

    public function getTypeAttribute(): string
    {
        if ($this->start->toDateString() !== $this->end->toDateString()) {
            return 3;
        }

        if (
            $this->start->format('H:i:s') !== '00:00:00' ||
            $this->end->format('H:i:s') !== '23:59:59'
        ) {
            return 2;
        }

        return 1;
    }
}
