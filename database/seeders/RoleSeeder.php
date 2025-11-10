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

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'student']);

        // Create permissions (optional)
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'manage-subjects']);
        Permission::create(['name' => 'manage-topics']);
        Permission::create(['name' => 'manage-questions']);
        Permission::create(['name' => 'manage-exams']);
        Permission::create(['name' => 'take-exams']);
        Permission::create(['name' => 'view-results']);

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
