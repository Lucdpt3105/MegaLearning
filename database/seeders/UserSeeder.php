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
        // Tạo Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@megalearning.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Tạo Teacher
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@megalearning.com',
            'password' => Hash::make('password'),
        ]);
        $teacher->assignRole('teacher');

        // Tạo Student
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@megalearning.com',
            'password' => Hash::make('password'),
        ]);
        $student->assignRole('student');
    }
}
