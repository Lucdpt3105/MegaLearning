<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@student.com'],
            ['name' => 'Trần Thị B', 'email' => 'tranthib@student.com'],
            ['name' => 'Lê Văn C', 'email' => 'levanc@student.com'],
            ['name' => 'Phạm Thị D', 'email' => 'phamthid@student.com'],
            ['name' => 'Hoàng Văn E', 'email' => 'hoangvane@student.com'],
            ['name' => 'Vũ Thị F', 'email' => 'vuthif@student.com'],
            ['name' => 'Đặng Văn G', 'email' => 'dangvang@student.com'],
            ['name' => 'Bùi Thị H', 'email' => 'buithih@student.com'],
            ['name' => 'Đinh Văn I', 'email' => 'dinhvani@student.com'],
            ['name' => 'Đỗ Thị K', 'email' => 'dothik@student.com'],
            ['name' => 'Ngô Văn L', 'email' => 'ngovanl@student.com'],
            ['name' => 'Mai Thị M', 'email' => 'maithim@student.com'],
            ['name' => 'Võ Văn N', 'email' => 'vovann@student.com'],
            ['name' => 'Dương Thị O', 'email' => 'duongthio@student.com'],
            ['name' => 'Phan Văn P', 'email' => 'phanvanp@student.com'],
        ];

        foreach ($students as $studentData) {
            $user = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
            ]);
            
            $user->assignRole('student');
        }

        echo "Created " . count($students) . " students.\n";
    }
}
