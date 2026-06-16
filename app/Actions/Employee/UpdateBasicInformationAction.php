<?php

namespace App\Actions\Employee;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBasicInformationAction
{
    use AsAction;

    public function handle(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
                $fillData = [
                    'first_name'   => $data['first_name'],
                    'last_name'    => $data['last_name'],
                    'email'        => $data['email'],
                    'phone'        => $data['phone'],
                ];
                if ($user->id != 1) {
                    $fillData = array_merge($fillData, [
                        'joining_date' => $user->joining_date,
                    ]);
                    $user->syncRoles([$data['role']]);
                }g

                $user->update($fillData);

                if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                    $user->clearMediaCollection('avatar');
                    $user->addMedia($data['avatar'])->toMediaCollection('avatar');
                }

            return $user->fresh(['roles', 'media']);
        });
    }
}