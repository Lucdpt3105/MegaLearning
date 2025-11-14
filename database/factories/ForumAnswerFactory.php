<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumAnswer>
 */
class ForumAnswerFactory extends Factory
{
    public function definition()
    {
        return [
            'forum_question_id' => null, // set in seeder when inserting
            'user_id' => null,
            'parent_id' => null,
            'answer_content' => $this->faker->paragraph(2),
            'created_at' => Carbon::now()->subMinutes(rand(0, 5000)),
        ];
    }
}