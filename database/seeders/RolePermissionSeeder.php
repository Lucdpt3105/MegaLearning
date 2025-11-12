<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'lock users',
            'manage permissions',

            // Content Moderation
            'approve documents',
            'reject documents',
            'approve exams',
            'reject exams',
            'delete documents',

            // Subject Management
            'view subjects',
            'create subjects',
            'edit subjects',
            'delete subjects',

            // Exam Management
            'view exams',
            'create exams',
            'edit exams',
            'delete exams',
            'grade exams',

            // Student Management
            'view students',
            'manage enrollments',
            'view grades',
            'view attendance',

            // Forum Management
            'moderate forum',
            'create threads',
            'reply threads',

            // Video Call
            'create video calls',
            'join video calls',

            // System
            'view logs',
            'view statistics',
            'manage system',
            'backup system',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view subjects',
            'create subjects',
            'edit subjects',
            'view exams',
            'create exams',
            'edit exams',
            'delete exams',
            'grade exams',
            'view students',
            'manage enrollments',
            'view grades',
            'view attendance',
            'moderate forum',
            'create threads',
            'reply threads',
            'create video calls',
            'join video calls',
        ]);

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view subjects',
            'view exams',
            'view grades',
            'create threads',
            'reply threads',
            'join video calls',
        ]);
    }
}
