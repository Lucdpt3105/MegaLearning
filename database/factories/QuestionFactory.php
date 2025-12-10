<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'created_by' => User::factory(),
            'question_text' => fake()->sentence() . '?',
            'type' => fake()->randomElement(['multiple_choice', 'true_false', 'essay']),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
            'marks' => fake()->numberBetween(1, 10),
            'options' => json_encode([
                'A' => fake()->sentence(),
                'B' => fake()->sentence(),
                'C' => fake()->sentence(),
                'D' => fake()->sentence(),
            ]),
            'correct_answer' => 'A',
            'explanation' => fake()->paragraph(),
        ];
    }
}
