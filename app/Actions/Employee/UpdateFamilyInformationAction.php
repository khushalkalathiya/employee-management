<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\FamilyInformation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFamilyInformationAction
{
    use AsAction;

    public function handle(User $user, array $data): FamilyInformation
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee()->firstOrCreate([], [
                'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            ]);

            $family = $employee->familyInformation()->firstOrCreate([]);
            $family->update($data);

            return $family;
        });
    }
}
