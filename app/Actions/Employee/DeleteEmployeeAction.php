<?php

namespace App\Actions\Employee;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteEmployeeAction
{
    use AsAction;

    public function handle(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->clearMediaCollection();
            return $user->delete();
        });
    }
}