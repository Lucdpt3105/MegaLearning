<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;
use App\Models\Question;
use App\Models\Answer;
use Carbon\Carbon;

class ExamSubmissionSeeder extends Seeder
{
    /**
     * Seed exam submissions with realistic scores for testing statistics
     */
    public function run(): void
    {
        // Get all students
        $students = User::role('student')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please seed users first.');
            return;
        }

        // Get all exams with their questions
        $exams = Exam::with('questions')->get();
        
        if ($exams->isEmpty()) {
            $this->command->warn('No exams found. Please create exams first.');
            return;
        }

        $this->command->info("Seeding exam submissions for {$students->count()} students across {$exams->count()} exams...");

        $submissionCount = 0;
        $scoreRanges = [
            [9, 10],    // Excellent (10%)
            [8, 9],     // Very Good (20%)
            [7, 8],     // Good (30%)
            [6, 7],     // Above Average (20%)
            [5, 6],     // Average (10%)
            [0, 5],     // Below Average (10%)
        ];

        foreach ($students as $student) {
            // Each student takes 2-5 random exams
            $numExams = rand(2, 5);
            $selectedExams = $exams->random(min($numExams, $exams->count()));

            foreach ($selectedExams as $exam) {
                // Skip if already has submission for this exam
                $existing = ExamSubmission::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->first();

                if ($existing) {
                    continue;
                }

                // Check if student is enrolled in the exam's class
                $enrollment = ClassEnrollment::where('student_id', $student->id)
                    ->where('class_room_id', $exam->class_room_id)
                    ->first();

                if (!$enrollment) {
                    // Enroll student in class
                    ClassEnrollment::create([
                        'student_id' => $student->id,
                        'class_room_id' => $exam->class_room_id,
                        'enrolled_at' => Carbon::now()->subDays(rand(30, 90)),
                    ]);
                }

                // Determine score range based on weighted distribution
                $rand = rand(1, 100);
                if ($rand <= 10) {
                    $range = $scoreRanges[0]; // 9-10 (10%)
                } elseif ($rand <= 30) {
                    $range = $scoreRanges[1]; // 8-9 (20%)
                } elseif ($rand <= 60) {
                    $range = $scoreRanges[2]; // 7-8 (30%)
                } elseif ($rand <= 80) {
                    $range = $scoreRanges[3]; // 6-7 (20%)
                } elseif ($rand <= 90) {
                    $range = $scoreRanges[4]; // 5-6 (10%)
                } else {
                    $range = $scoreRanges[5]; // 0-5 (10%)
                }

                $score = rand($range[0] * 10, $range[1] * 10) / 10;

                // Create submission
                $submission = ExamSubmission::create([
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                    'status' => 'submitted',
                    'grading_status' => 'graded',
                    'score' => $score,
                    'feedback' => $this->generateFeedback($score),
                    'submitted_at' => Carbon::now()->subDays(rand(1, 30)),
                    'graded_at' => Carbon::now()->subDays(rand(0, 15)),
                ]);

                // Create answers for questions
                $questions = $exam->questions;
                $questionsToAnswer = $questions->random(min($questions->count(), rand(ceil($questions->count() * 0.7), $questions->count())));

                foreach ($questionsToAnswer as $question) {
                    // Get a random answer (correct or incorrect based on score)
                    $correctProbability = $score / 10; // Higher score = more correct answers
                    $shouldBeCorrect = (rand(1, 100) / 100) <= $correctProbability;

                    $answers = Answer::where('question_id', $question->id)->get();
                    if ($answers->isEmpty()) continue;

                    if ($shouldBeCorrect) {
                        $answer = $answers->where('is_correct', true)->first() ?? $answers->random();
                    } else {
                        $incorrectAnswers = $answers->where('is_correct', false);
                        $answer = $incorrectAnswers->isNotEmpty() ? $incorrectAnswers->random() : $answers->random();
                    }

                    // Store selected answer in submission
                    \DB::table('exam_submission_answers')->insert([
                        'submission_id' => $submission->id,
                        'question_id' => $question->id,
                        'answer_id' => $answer->id,
                        'created_at' => $submission->submitted_at,
                        'updated_at' => $submission->submitted_at,
                    ]);
                }

                $submissionCount++;
            }
        }

        $this->command->info("✅ Created {$submissionCount} exam submissions with realistic scores");
        
        // Show distribution
        $this->command->info("\nScore Distribution:");
        foreach ($scoreRanges as $range) {
            $count = ExamSubmission::where('grading_status', 'graded')
                ->whereBetween('score', $range)
                ->count();
            $this->command->info("   {$range[0]}-{$range[1]}: {$count} submissions");
        }
    }

    /**
     * Generate feedback based on score
     */
    private function generateFeedback($score): string
    {
        if ($score >= 9) {
            return 'Xuất sắc! Bạn đã nắm vững kiến thức và làm bài rất tốt.';
        } elseif ($score >= 8) {
            return 'Rất tốt! Bạn đã hiểu bài và làm bài chính xác.';
        } elseif ($score >= 7) {
            return 'Tốt! Bạn đã làm bài đúng phần lớn câu hỏi.';
        } elseif ($score >= 6) {
            return 'Khá! Bạn cần ôn tập thêm một số phần kiến thức.';
        } elseif ($score >= 5) {
            return 'Trung bình. Bạn nên xem lại bài giảng và làm thêm bài tập.';
        } else {
            return 'Cần cố gắng hơn. Hãy xem lại bài giảng và hỏi giáo viên nếu có thắc mắc.';
        }
    }
}
