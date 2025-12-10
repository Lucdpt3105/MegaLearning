<?php

namespace Database\Factories;

use App\Models\ExamSubmission;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamSubmissionFactory extends Factory
{
    protected $model = ExamSubmission::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'student_id' => User::factory(),
            'answers' => json_encode([
                1 => 'A',
                2 => 'B',
                3 => 'C',
            ]),
            'marks_obtained' => fake()->numberBetween(0, 100),
            'time_spent' => fake()->numberBetween(10, 120),
            'status' => 'submitted',
            'grading_status' => 'pending',
            'submitted_at' => now(),
        ];
    }
}
