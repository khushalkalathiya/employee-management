<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'superadmin@gmail.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'password' => Hash::make('123456'),
        ]);

        $user->assignRole('superadmin');
    }
}