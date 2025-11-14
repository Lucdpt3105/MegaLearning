<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumQuestion>
 */
class ForumQuestionFactory extends Factory
{
    public function definition()
    {
        return [
            // note: migrations use forum_question_id as PK; factory creates attributes only
            'user_id' => null, // set in seeder when using DB insert
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraph(3),
            'created_at' => Carbon::now()->subMinutes(rand(0, 5000)),
        ];
    }
}