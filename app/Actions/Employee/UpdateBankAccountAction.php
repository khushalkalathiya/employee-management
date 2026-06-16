<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBankAccountAction
{
    use AsAction;

    public function handle(User $user, array $data): BankAccount
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $bankAccount = $employee->bankAccount;
            if($bankAccount == null){
                $bankAccount = $employee->bankAccount()->create($data);
            } else {
                $bankAccount->update($data);
            }

            return $bankAccount;
        });
    }
}
