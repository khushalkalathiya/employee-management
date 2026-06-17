<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\Education;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEducationAction
{
    use AsAction;

    public function handle(User $user, array $data): Education
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            return $employee->education()->create($data);
        });
    }
}
