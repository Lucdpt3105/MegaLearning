<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create roles (idempotent)
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'student']);

    // Create permissions (idempotent)
    Permission::firstOrCreate(['name' => 'manage-users']);
    Permission::firstOrCreate(['name' => 'manage-subjects']);
    Permission::firstOrCreate(['name' => 'manage-topics']);
    Permission::firstOrCreate(['name' => 'manage-questions']);
    Permission::firstOrCreate(['name' => 'manage-exams']);
    Permission::firstOrCreate(['name' => 'take-exams']);
    Permission::firstOrCreate(['name' => 'view-results']);

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all()); // Admin có tất cả quyền

        $teacherRole = Role::findByName('teacher');
        $teacherRole->givePermissionTo([
            'manage-subjects',
            'manage-topics',
            'manage-questions',
            'manage-exams',
            'view-results',
        ]);

        $studentRole = Role::findByName('student');
        $studentRole->givePermissionTo([
            'take-exams',
            'view-results',
        ]);
    }
}
