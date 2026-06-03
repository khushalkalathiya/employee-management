<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_name',
        'document_number',
    ];
}
