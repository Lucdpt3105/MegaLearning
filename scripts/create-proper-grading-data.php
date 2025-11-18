<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\User;

// Load environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎓 Creating PROPER test data for auto-grading...\n\n";

DB::beginTransaction();

try {
    // Get teacher
    $teacher = User::role('teacher')->first();
    echo "✅ Teacher: {$teacher->name}\n";

    // Get exam
    $exam = Exam::where('created_by', $teacher->id)->first();
    echo "✅ Exam: {$exam->title}\n";

    // Get questions with answers
    $examQuestions = $exam->questions()
        ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
        ->orderBy('exam_questions.order')
        ->get();

    echo "✅ Questions: {$examQuestions->count()}\n\n";

    // Delete old test submissions (only test students)
    $testEmails = ['test.student.0@test.com', 'test.student.1@test.com', 'test.student.2@test.com'];
    $testStudentIds = User::whereIn('email', $testEmails)->pluck('id');
    ExamSubmission::where('exam_id', $exam->id)
        ->whereIn('student_id', $testStudentIds)
        ->delete();
    echo "🗑️ Deleted old test submissions\n\n";

    // Create 3 test students
    $scenarios = [
        ['name' => 'Test Student A (100%)', 'email' => 'test.student.0@test.com', 'scenario' => 0],
        ['name' => 'Test Student B (50%)', 'email' => 'test.student.1@test.com', 'scenario' => 1],
        ['name' => 'Test Student C (0%)', 'email' => 'test.student.2@test.com', 'scenario' => 2],
    ];
    
    echo "📝 Creating new submissions with proper answers...\n\n";

    foreach ($scenarios as $config) {
        // Get or create test student
        $student = User::where('email', $config['email'])->first();
        if (!$student) {
            $student = User::create([
                'name' => $config['name'],
                'email' => $config['email'],
                'password' => bcrypt('password'),
            ]);
            $student->assignRole('student');
        }

        $scenario = $config['scenario']; // 0 = all correct, 1 = 50%, 2 = all wrong
        $studentName = $student->name;
        $answers = [];

        echo "  Creating submission for: {$studentName}\n";

        foreach ($examQuestions as $qIndex => $question) {
            $questionId = $question->id;
            $type = $question->pivot->custom_type ?? $question->type;
            
            // Get correct answer
            $correctAnswer = null;
            
            // Decode custom_answers if it's a JSON string
            $customAnswers = $question->pivot->custom_answers;
            if (is_string($customAnswers)) {
                $customAnswers = json_decode($customAnswers, true);
            }
            
            if ($customAnswers && isset($customAnswers['correct_answer'])) {
                $correctAnswer = $customAnswers['correct_answer'];
            } elseif ($question->correct_answer) {
                $correctAnswer = $question->correct_answer;
            }

            if ($type === 'multiple_choice') {
                if ($correctAnswer) {
                    // Scenario logic
                    if ($scenario === 0) {
                        // All correct
                        $answers[$questionId] = $correctAnswer;
                    } elseif ($scenario === 1) {
                        // 50% correct - alternate
                        if ($qIndex % 2 === 0) {
                            $answers[$questionId] = $correctAnswer;
                        } else {
                            // Wrong answer
                            $wrongOptions = ['A', 'B', 'C', 'D'];
                            $wrongOptions = array_diff($wrongOptions, [$correctAnswer]);
                            $answers[$questionId] = array_values($wrongOptions)[0];
                        }
                    } else {
                        // All wrong
                        $wrongOptions = ['A', 'B', 'C', 'D'];
                        $wrongOptions = array_diff($wrongOptions, [$correctAnswer]);
                        $answers[$questionId] = array_values($wrongOptions)[0];
                    }
                    
                    echo "    Q{$qIndex}: {$type} - Correct: {$correctAnswer}, Student: {$answers[$questionId]}\n";
                } else {
                    echo "    ⚠️ Q{$qIndex}: No correct answer defined, skipping\n";
                }

            } elseif ($type === 'true_false') {
                if ($correctAnswer) {
                    if ($scenario === 0) {
                        $answers[$questionId] = $correctAnswer;
                    } else {
                        $answers[$questionId] = $correctAnswer === 'true' ? 'false' : 'true';
                    }
                    echo "    Q{$qIndex}: {$type} - Correct: {$correctAnswer}, Student: {$answers[$questionId]}\n";
                } else {
                    echo "    ⚠️ Q{$qIndex}: No correct answer defined, skipping\n";
                }

            } elseif (in_array($type, ['essay', 'fill_blank'])) {
                // Essay questions
                $essayText = match($scenario) {
                    0 => "Đây là câu trả lời xuất sắc với đầy đủ lý lẽ và dẫn chứng.",
                    1 => "Câu trả lời khá tốt nhưng còn thiếu một vài chi tiết.",
                    2 => "Câu trả lời quá ngắn gọn và không đầy đủ.",
                };
                $answers[$questionId] = $essayText;
                echo "    Q{$qIndex}: {$type} - Essay answer\n";
            }
        }

        // Create submission
        $submission = ExamSubmission::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'attempt_number' => 1,
            'started_at' => now()->subMinutes(40),
            'submitted_at' => now()->subMinutes(5),
            'time_spent' => 35,
            'answers' => $answers,
            'status' => 'submitted',
            'grading_status' => 'pending',
        ]);

        echo "    ✅ Created submission ID: {$submission->id}\n";
        echo "    📊 Total answers: " . count($answers) . "\n\n";
    }

    DB::commit();

    echo "\n🎉 Test data created successfully!\n\n";
    echo "📌 Now go to: http://localhost:8000/teacher/grading\n";
    echo "📌 Try 'Chấm tự động' and you should see:\n";
    echo "   - Student A: ~100% (all multiple choice correct)\n";
    echo "   - Student B: ~50% (half correct)\n";
    echo "   - Student C: ~0% (all wrong)\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
