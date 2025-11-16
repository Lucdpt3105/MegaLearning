<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo Admin (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'admin@megalearning.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Tạo Teacher (idempotent)
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@megalearning.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$teacher->hasRole('teacher')) {
            $teacher->assignRole('teacher');
        }

        // Tạo Student (idempotent)
        $student = User::firstOrCreate(
            ['email' => 'student@megalearning.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$student->hasRole('student')) {
            $student->assignRole('student');
        }
    }
}
