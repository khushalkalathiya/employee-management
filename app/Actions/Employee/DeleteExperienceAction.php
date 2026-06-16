<?php

namespace App\Actions\Employee;

use App\Models\Experience;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteExperienceAction
{
    use AsAction;

    public function handle(Experience $experience): bool
    {
        return DB::transaction(function () use ($experience) {
            return (bool) $experience->delete();
        });
    }
}
