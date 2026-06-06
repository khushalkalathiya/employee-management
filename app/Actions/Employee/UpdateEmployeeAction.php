<?php

namespace App\Actions\Employee;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployeeAction
{
    use AsAction;

    public function handle(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $user->update([
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'joining_date' => $data['joining_date'] ?? null,
                'gender'       => $data['gender'] ?? null,
            ]);

            $user->syncRoles([$data['role']]);

            if (
                isset($data['avatar']) &&
                $data['avatar'] instanceof UploadedFile
            ) {
                $user->clearMediaCollection('avatar');

                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }

            return $user->fresh(['roles', 'media']);
        });
    }
}