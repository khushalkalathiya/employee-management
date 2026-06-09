<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Leave Types
        $leaveTypes = [
            [
                'name' => 'Casual Leave',
                'monthly_limit' => 2,
                'description' => 'Personal work, urgent tasks, short planned absences.',
            ],
            [
                'name' => 'Sick Leave',
                'monthly_limit' => 2,
                'description' => 'Medical illness, health issues, doctor consultation.',
            ],
            [
                'name' => 'Earned Leave',
                'monthly_limit' => 1,
                'description' => 'Paid leave accumulated through service.',
            ],
            [
                'name' => 'Vacation Leave',
                'monthly_limit' => 3,
                'description' => 'Family trips, holidays, personal travel.',
            ],
            [
                'name' => 'Maternity Leave',
                'monthly_limit' => null,
                'description' => 'Leave granted for childbirth and recovery.',
            ],
            [
                'name' => 'Paternity Leave',
                'monthly_limit' => null,
                'description' => 'Leave granted to fathers after childbirth.',
            ],
            [
                'name' => 'Compensatory Leave',
                'monthly_limit' => 2,
                'description' => 'Leave earned by working on holidays or weekends.',
            ],
            [
                'name' => 'Work From Home',
                'monthly_limit' => 5,
                'description' => 'Remote working request instead of office attendance.',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::firstOrCreate(
                ['name' => $leaveType['name']],
                $leaveType
            );
        }

    }
}