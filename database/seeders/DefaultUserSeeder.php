<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'role' => 'superadmin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@gmail.com',
            ],
            [
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
            ],
            [
                'role' => 'hr',
                'first_name' => 'HR',
                'last_name' => 'Manager',
                'email' => 'hr@gmail.com',
            ],
            [
                'role' => 'manager',
                'first_name' => 'Department',
                'last_name' => 'Manager',
                'email' => 'manager@gmail.com',
            ],
            [
                'role' => 'employee',
                'first_name' => 'Test',
                'last_name' => 'Employee',
                'email' => 'employee@gmail.com',
            ],
        ];

        foreach ($users as $data) {

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make('123456'),
                    'is_active' => true,
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}