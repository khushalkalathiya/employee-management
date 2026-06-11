<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'superadmin' => 'Super Admin',
            'admin'      => 'Admin',
            'hr'         => 'HR',
            'manager'    => 'Manager',
            'employee'   => 'Employee',
        ];

        foreach ($roles as $name => $displayName) {
            Role::create([
                'name' => $name,
                'guard_name' => 'web',
                'display_name' => $displayName,
            ]);
        }
        
        $permissions = [

            // Roles
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            // Employees
            'employee.view',
            'employee.create',
            'employee.edit',
            'employee.delete',

            // Departments
            'department.view',
            'department.create',
            'department.edit',
            'department.delete',

            // Designations
            'designation.view',
            'designation.create',
            'designation.edit',
            'designation.delete',

            // Attendance
            'attendance.view',
            'attendance.create',
            'attendance.edit',
            'attendance.delete',
            'attendance.own',

            // Leave Types
            'leave_type.view',
            'leave_type.create',
            'leave_type.edit',
            'leave_type.delete',
            
            // Leave Management Permissions
            'leave.view',
            'leave.create',
            'leave.edit',
            'leave.delete',
            'leave.own',

            // Holidays
            'holiday.view',
            'holiday.create',
            'holiday.edit',
            'holiday.delete',

            // Settings
            'settings.general.view',
            'settings.general.edit',
            'settings.work_schedule.view',
            'settings.work_schedule.edit',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::findByName('superadmin');

        $superAdmin->syncPermissions(
            Permission::whereNotIn('name', [
                'leave.own',
            ])->get()
        );
    }
}
