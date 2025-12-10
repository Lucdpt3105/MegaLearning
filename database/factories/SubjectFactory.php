<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'code' => strtoupper(fake()->bothify('???###')),
            'description' => fake()->sentence(),
            'teacher_id' => User::factory(),
            'credits' => fake()->numberBetween(2, 4),
            'semester' => fake()->numberBetween(1, 8),
            'status' => 'active',
        ];
    }
}
