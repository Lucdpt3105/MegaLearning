<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'subject_id' => Subject::factory(),
            'created_by' => User::factory(),
            'duration' => fake()->numberBetween(30, 180),
            'total_marks' => fake()->numberBetween(50, 100),
            'passing_marks' => fake()->numberBetween(30, 50),
            'type' => fake()->randomElement(['quiz', 'midterm', 'final', 'practice']),
            'status' => 'draft',
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(7),
            'instructions' => fake()->paragraph(),
        ];
    }
}
