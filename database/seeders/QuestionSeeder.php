<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::role('teacher')->first();
        
        if (!$teacher) {
            $this->command->warn('No teacher found. Please run UserSeeder first.');
            return;
        }

        $subjects = Subject::where('teacher_id', $teacher->id)->get();

        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Please run SubjectSeeder first.');
            return;
        }

        foreach ($subjects as $subject) {
            // Create Multiple Choice Questions
            $this->createMultipleChoiceQuestions($subject, $teacher);
            
            // Create True/False Questions
            $this->createTrueFalseQuestions($subject, $teacher);
            
            // Create Essay Questions
            $this->createEssayQuestions($subject, $teacher);
            
            // Create Fill Blank Questions
            $this->createFillBlankQuestions($subject, $teacher);
        }

        $this->command->info('Question bank seeded successfully!');
    }

    private function createMultipleChoiceQuestions($subject, $teacher)
    {
        $questions = [
            [
                'content' => 'Laravel là gì?',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'Laravel là một PHP framework mã nguồn mở phổ biến.',
                'answers' => [
                    ['content' => 'Một PHP framework', 'is_correct' => true],
                    ['content' => 'Một ngôn ngữ lập trình', 'is_correct' => false],
                    ['content' => 'Một database', 'is_correct' => false],
                    ['content' => 'Một IDE', 'is_correct' => false],
                ]
            ],
            [
                'content' => 'MVC là viết tắt của từ gì?',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'MVC là Model-View-Controller, một mẫu kiến trúc phần mềm.',
                'answers' => [
                    ['content' => 'Model-View-Controller', 'is_correct' => true],
                    ['content' => 'Make-View-Code', 'is_correct' => false],
                    ['content' => 'Main-Visual-Component', 'is_correct' => false],
                    ['content' => 'Module-Validation-Control', 'is_correct' => false],
                ]
            ],
            [
                'content' => 'Eloquent ORM trong Laravel dùng để làm gì?',
                'difficulty' => 'medium',
                'points' => 2,
                'explanation' => 'Eloquent ORM giúp tương tác với database một cách dễ dàng.',
                'answers' => [
                    ['content' => 'Quản lý database và tương tác với dữ liệu', 'is_correct' => true],
                    ['content' => 'Xử lý routing', 'is_correct' => false],
                    ['content' => 'Render view', 'is_correct' => false],
                    ['content' => 'Xử lý authentication', 'is_correct' => false],
                ]
            ],
        ];

        foreach ($questions as $questionData) {
            $answers = $questionData['answers'];
            unset($questionData['answers']);

            $question = Question::create(array_merge($questionData, [
                'type' => 'multiple_choice',
                'subject_id' => $subject->id,
                'created_by' => $teacher->id,
                'in_question_bank' => true,
            ]));

            foreach ($answers as $index => $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $answerData['content'],
                    'is_correct' => $answerData['is_correct'],
                    'order' => $index + 1,
                ]);
            }
        }
    }

    private function createTrueFalseQuestions($subject, $teacher)
    {
        $questions = [
            [
                'content' => 'Laravel sử dụng Composer để quản lý dependencies.',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'Composer là công cụ quản lý dependencies chính thức của PHP.',
                'answer' => true,
            ],
            [
                'content' => 'Blade là template engine của Laravel.',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'Blade là template engine mạnh mẽ và dễ sử dụng của Laravel.',
                'answer' => true,
            ],
            [
                'content' => 'Laravel chỉ hỗ trợ MySQL database.',
                'difficulty' => 'medium',
                'points' => 1,
                'explanation' => 'Laravel hỗ trợ nhiều loại database như MySQL, PostgreSQL, SQLite, SQL Server.',
                'answer' => false,
            ],
        ];

        foreach ($questions as $questionData) {
            $correctAnswer = $questionData['answer'];
            unset($questionData['answer']);

            $question = Question::create(array_merge($questionData, [
                'type' => 'true_false',
                'subject_id' => $subject->id,
                'created_by' => $teacher->id,
                'in_question_bank' => true,
            ]));

            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đúng',
                'is_correct' => $correctAnswer === true,
                'order' => 1,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'content' => 'Sai',
                'is_correct' => $correctAnswer === false,
                'order' => 2,
            ]);
        }
    }

    private function createEssayQuestions($subject, $teacher)
    {
        $questions = [
            [
                'content' => 'Giải thích sự khác biệt giữa GET và POST request trong HTTP.',
                'difficulty' => 'medium',
                'points' => 5,
                'explanation' => 'GET được sử dụng để lấy dữ liệu, POST để gửi dữ liệu lên server.',
            ],
            [
                'content' => 'Mô tả vòng đời của một request trong Laravel.',
                'difficulty' => 'hard',
                'points' => 10,
                'explanation' => 'Request đi qua middleware, router, controller, và trả về response.',
            ],
        ];

        foreach ($questions as $questionData) {
            Question::create(array_merge($questionData, [
                'type' => 'essay',
                'subject_id' => $subject->id,
                'created_by' => $teacher->id,
                'in_question_bank' => true,
            ]));
        }
    }

    private function createFillBlankQuestions($subject, $teacher)
    {
        $questions = [
            [
                'content' => 'Command để tạo một controller mới trong Laravel là ___.',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'Sử dụng: php artisan make:controller ControllerName',
            ],
            [
                'content' => 'File cấu hình database của Laravel nằm trong thư mục ___.',
                'difficulty' => 'easy',
                'points' => 1,
                'explanation' => 'File database.php nằm trong thư mục config/',
            ],
        ];

        foreach ($questions as $questionData) {
            Question::create(array_merge($questionData, [
                'type' => 'fill_blank',
                'subject_id' => $subject->id,
                'created_by' => $teacher->id,
                'in_question_bank' => true,
            ]));
        }
    }
}

