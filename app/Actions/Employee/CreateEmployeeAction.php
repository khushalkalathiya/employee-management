<?php

namespace App\Actions\Employee;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEmployeeAction
{
    use AsAction;

    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'first_name'   => $data['first_name'] ?? '',
                'last_name'    => $data['last_name'] ?? '',
                'email'        => $data['email'] ?? '',
                'phone'        => $data['phone'] ?? '',
                'joining_date' => $data['joining_date'] ?? null,
                'password'     => Hash::make($data['password']),
                'gender'       => $data['gender'] ?? null,
            ]);

            $user->employee()->create([
                'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            ]);

            $user->assignRole($data['role']);

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }
            
            return $user;
        });
    }
}