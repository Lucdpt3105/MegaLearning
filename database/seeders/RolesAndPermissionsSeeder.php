<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Admin
        $adminPermissions = [
            'manage users',
            'manage roles',
            'approve content',
            'approve exams',
            'manage attendance',
            'system admin',
            'view statistics',
            'manage video rooms',
        ];

        // Create permissions for Teacher
        $teacherPermissions = [
            'manage subjects',
            'manage topics',
            'create exams',
            'grade exams',
            'manage questions',
            'manage documents',
            'manage students',
            'conduct classes',
            'moderate forum',
        ];

        // Create permissions for Student
        $studentPermissions = [
            'view documents',
            'take exams',
            'view grades',
            'join classes',
            'participate forum',
        ];

        // Create all permissions
        foreach (array_merge($adminPermissions, $teacherPermissions, $studentPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($adminPermissions);

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo($teacherPermissions);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo($studentPermissions);

        // AI role for chatbot (special role)
        $aiRole = Role::firstOrCreate(['name' => 'ai']);

        $this->command->info('✅ Roles and Permissions created successfully!');
        $this->command->info('   - Admin role with ' . count($adminPermissions) . ' permissions');
        $this->command->info('   - Teacher role with ' . count($teacherPermissions) . ' permissions');
        $this->command->info('   - Student role with ' . count($studentPermissions) . ' permissions');
        $this->command->info('   - AI role (special)');
    }
}
