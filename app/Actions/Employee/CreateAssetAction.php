<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAssetAction
{
    use AsAction;

    public function handle(User $user, array $data): Asset
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee()->firstOrCreate([], [
                'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            ]);

            return $employee->assets()->create($data);
        });
    }
}
