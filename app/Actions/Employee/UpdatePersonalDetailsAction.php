<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePersonalDetailsAction
{
    use AsAction;

    public function handle(User $user, array $data): Employee
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $employee->update($data);

            return $employee;
        });
    }
}
