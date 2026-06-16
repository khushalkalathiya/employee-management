<?php

namespace App\Actions\Employee;

use App\Models\Education;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteEducationAction
{
    use AsAction;

    public function handle(Education $education): bool
    {
        return DB::transaction(function () use ($education) {
            return (bool) $education->delete();
        });
    }
}
