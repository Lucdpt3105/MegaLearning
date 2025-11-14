<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();
        $userIds = DB::table('users')->limit(6)->pluck('id')->toArray();

        // 2) Forum questions
        $questionIds = [];
        for ($i = 0; $i < 12; $i++) {
            $questionIds[] = DB::table('forumquestions')->insertGetId([
                'user_id' => $faker->randomElement($userIds),
                'title' => $faker->sentence(6),
                'content' => $faker->paragraph(3),
                'created_at' => $now->subMinutes(rand(0, 5000)),
            ], 'forum_question_id');
        }

        // 3) Forum answers (with optional parent replies)
        $answerIds = [];
        foreach ($questionIds as $qId) {
            $count = rand(0, 5);
            $localAnswers = [];
            for ($j = 0; $j < $count; $j++) {
                $aid = DB::table('forumanswers')->insertGetId([
                    'forum_question_id' => $qId,
                    'user_id' => $faker->randomElement($userIds),
                    'parent_id' => null,
                    'answer_content' => $faker->paragraph(2),
                    'created_at' => $now->subMinutes(rand(0, 5000)),
                ], 'forum_answer_id');
                $localAnswers[] = $aid;
                $answerIds[] = $aid;
            }

            // create a few replies referencing parent answers
            if (count($localAnswers) && rand(0,1)) {
                $parent = $faker->randomElement($localAnswers);
                $replyId = DB::table('forumanswers')->insertGetId([
                    'forum_question_id' => $qId,
                    'user_id' => $faker->randomElement($userIds),
                    'parent_id' => $parent,
                    'answer_content' => $faker->paragraph(1),
                    'created_at' => $now->subMinutes(rand(0, 5000)),
                ], 'forum_answer_id');
                $answerIds[] = $replyId;
            }
        }

        // 4) Votes - avoid duplicates by simple checks
        foreach ($questionIds as $qId) {
            $voters = $faker->randomElements($userIds, rand(1, min(4, count($userIds))));
            foreach ($voters as $uid) {
                DB::table('votes')->insert([
                    'user_id' => $uid,
                    'forum_question_id' => $qId,
                    'forum_answer_id' => null,
                    'value' => $faker->randomElement([1, 1, -1]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        foreach ($answerIds as $aid) {
            if ($faker->boolean(30)) {
                $voters = $faker->randomElements($userIds, rand(1, min(3, count($userIds))));
                foreach ($voters as $uid) {
                    DB::table('votes')->insert([
                        'user_id' => $uid,
                        'forum_question_id' => null,
                        'forum_answer_id' => $aid,
                        'value' => $faker->randomElement([1, -1]),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}