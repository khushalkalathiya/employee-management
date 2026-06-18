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
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $familyInformation = $employee->familyInformation;
            if($familyInformation == null){
                $familyInformation = $employee->familyInformation()->create($data);
            } else {
                $familyInformation->update($data);
            }

            return $familyInformation;
        });
    }
}
