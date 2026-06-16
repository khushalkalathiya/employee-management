<?php

namespace App\Actions\Employee;

use App\Models\Education;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEducationAction
{
    use AsAction;

    public function handle(Education $education, array $data): Education
    {
        return DB::transaction(function () use ($education, $data) {
            $education->update($data);
            return $education;
        });
    }
}
