<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo Subjects (Môn học)
        $subjects = [
            ['name' => 'Toán học', 'code' => 'MATH', 'description' => 'Môn Toán học'],
            ['name' => 'Vật lý', 'code' => 'PHY', 'description' => 'Môn Vật lý'],
            ['name' => 'Hóa học', 'code' => 'CHEM', 'description' => 'Môn Hóa học'],
            ['name' => 'Tiếng Anh', 'code' => 'ENG', 'description' => 'Môn Tiếng Anh'],
            ['name' => 'Ngữ văn', 'code' => 'LIT', 'description' => 'Môn Ngữ văn'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }

        // 2. Tạo Teachers
        $teachers = [];
        for ($i = 1; $i <= 5; $i++) {
            $teacher = User::firstOrCreate(
                ['email' => "teacher{$i}@example.com"],
                [
                    'name' => "Giáo viên {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $teacher->assignRole('teacher');
            $teachers[] = $teacher;
        }

        // 3. Tạo Students
        for ($i = 1; $i <= 20; $i++) {
            $student = User::firstOrCreate(
                ['email' => "student{$i}@example.com"],
                [
                    'name' => "Học sinh {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $student->assignRole('student');
        }

        // 4. Tạo ClassRooms (Courses)
        $subjectsList = Subject::all();
        foreach ($subjectsList as $index => $subject) {
            $teacher = $teachers[$index % count($teachers)];
            
            ClassRoom::firstOrCreate(
                ['name' => "Lớp {$subject->name} - K2024"],
                [
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'description' => "Khóa học {$subject->name} cho học sinh năm 2024",
                    'status' => 'active',
                    'max_students' => 30,
                ]
            );
        }

        // 5. Tạo Topics
        foreach ($subjectsList as $subject) {
            for ($i = 1; $i <= 3; $i++) {
                Topic::firstOrCreate(
                    ['name' => "Chủ đề {$i} - {$subject->name}"],
                    [
                        'subject_id' => $subject->id,
                        'description' => "Mô tả chủ đề {$i}",
                        'order' => $i,
                    ]
                );
            }
        }

        // 6. Tạo Questions (Câu hỏi)
        $topics = Topic::all();
        $questionTypes = ['multiple_choice', 'true_false', 'essay'];
        
        foreach ($topics as $topic) {
            for ($i = 1; $i <= 5; $i++) {
                Question::firstOrCreate(
                    [
                        'subject_id' => $topic->subject_id,
                        'topic_id' => $topic->id,
                        'question_text' => "Câu hỏi {$i} thuộc {$topic->name}",
                    ],
                    [
                        'question_type' => $questionTypes[array_rand($questionTypes)],
                        'options' => json_encode(['A' => 'Đáp án A', 'B' => 'Đáp án B', 'C' => 'Đáp án C', 'D' => 'Đáp án D']),
                        'correct_answer' => 'A',
                        'points' => 10,
                        'explanation' => 'Giải thích đáp án',
                    ]
                );
            }
        }

        // 7. Tạo Exams (Bài thi)
        foreach ($subjectsList as $subject) {
            for ($i = 1; $i <= 2; $i++) {
                Exam::firstOrCreate(
                    ['title' => "Kiểm tra {$i} - {$subject->name}"],
                    [
                        'subject_id' => $subject->id,
                        'description' => "Bài kiểm tra {$i} môn {$subject->name}",
                        'duration' => 60,
                        'total_marks' => 100,
                        'passing_marks' => 50,
                        'start_time' => now()->addDays($i),
                        'end_time' => now()->addDays($i + 7),
                        'status' => 'draft',
                    ]
                );
            }
        }

        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('📚 Created: ' . Subject::count() . ' subjects');
        $this->command->info('👨‍🏫 Created: ' . User::role('teacher')->count() . ' teachers');
        $this->command->info('👨‍🎓 Created: ' . User::role('student')->count() . ' students');
        $this->command->info('🏫 Created: ' . ClassRoom::count() . ' courses');
        $this->command->info('📝 Created: ' . Topic::count() . ' topics');
        $this->command->info('❓ Created: ' . Question::count() . ' questions');
        $this->command->info('📋 Created: ' . Exam::count() . ' exams');
    }
}
