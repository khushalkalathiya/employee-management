<?php

namespace App\Actions\Employee;

use App\Models\Experience;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateExperienceAction
{
    use AsAction;

    public function handle(Experience $experience, array $data): Experience
    {
        return DB::transaction(function () use ($experience, $data) {
            $experience->update($data);
            return $experience;
        });
    }
}
