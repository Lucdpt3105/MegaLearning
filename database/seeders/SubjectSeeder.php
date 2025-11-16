<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\User;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@megalearning.com')->first();

        if (!$teacher) {
            $this->command->error('Teacher user not found. Please run UserSeeder first.');
            return;
        }

        $subjects = [
            [
                'name' => 'Toán học',
                'code' => 'MATH101',
                'description' => 'Môn học về toán học cơ bản và nâng cao',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ],
            [
                'name' => 'Vật lý',
                'code' => 'PHYS101',
                'description' => 'Môn học về vật lý đại cương',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ],
            [
                'name' => 'Hóa học',
                'code' => 'CHEM101',
                'description' => 'Môn học về hóa học cơ bản',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ],
            [
                'name' => 'Lập trình Web',
                'code' => 'WEB101',
                'description' => 'Môn học về phát triển web với Laravel và Vue.js',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ],
            [
                'name' => 'Cơ sở dữ liệu',
                'code' => 'DB101',
                'description' => 'Môn học về thiết kế và quản trị cơ sở dữ liệu',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }

        $this->command->info('Subjects seeded successfully!');
    }
}
