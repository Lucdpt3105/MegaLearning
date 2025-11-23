<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => null,
            'forum_question_id' => null,
            'forum_answer_id' => null,
            'value' => $this->faker->randomElement([1, -1]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}