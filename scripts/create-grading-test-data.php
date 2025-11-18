<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\User;
use App\Models\Question;

// Load environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎓 Creating test data for grading feature...\n\n";

DB::beginTransaction();

try {
    // Get current teacher
    $teacher = User::role('teacher')->first();
    if (!$teacher) {
        echo "❌ No teacher found! Please create a teacher first.\n";
        exit(1);
    }
    echo "✅ Teacher: {$teacher->name}\n";

    // Get or create a test exam
    $exam = Exam::where('created_by', $teacher->id)->first();
    if (!$exam) {
        echo "❌ No exam found! Please create an exam first.\n";
        exit(1);
    }
    echo "✅ Exam: {$exam->title}\n";

    // Get questions from exam
    $questions = $exam->questions()->get();
    if ($questions->isEmpty()) {
        echo "❌ Exam has no questions! Please add questions to the exam.\n";
        exit(1);
    }
    echo "✅ Questions: {$questions->count()}\n\n";

    // Get or create test students
    $students = User::role('student')->take(3)->get();
    if ($students->count() < 3) {
        echo "⚠️ Not enough students. Creating test students...\n";
        for ($i = $students->count(); $i < 3; $i++) {
            $student = User::create([
                'name' => "Test Student " . ($i + 1),
                'email' => "student" . ($i + 1) . "@test.com",
                'password' => bcrypt('password'),
            ]);
            $student->assignRole('student');
            $students->push($student);
            echo "  ✅ Created: {$student->name}\n";
        }
    }

    echo "\n📝 Creating exam submissions...\n";

    foreach ($students as $index => $student) {
        // Check if submission already exists
        $existing = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            echo "  ⏭️ Skipping {$student->name} - already has submission\n";
            continue;
        }

        // Generate random answers
        $answers = [];
        $scenario = $index % 3; // Different scenarios

        foreach ($questions as $qIndex => $question) {
            $type = $question->pivot->custom_type ?? $question->type;
            $questionId = $question->id;

            if ($type === 'multiple_choice') {
                // Scenario 0: All correct, 1: Some wrong, 2: All wrong
                $correctAnswer = $question->pivot->custom_answers['correct_answer'] ?? $question->correct_answer;
                
                if ($scenario === 0) {
                    $answers[$questionId] = $correctAnswer; // Correct
                } elseif ($scenario === 1) {
                    $answers[$questionId] = $qIndex % 2 === 0 ? $correctAnswer : 'B'; // Some correct
                } else {
                    $answers[$questionId] = $correctAnswer === 'A' ? 'B' : 'A'; // Wrong
                }

            } elseif ($type === 'true_false') {
                $correctAnswer = $question->pivot->custom_answers['correct_answer'] ?? $question->correct_answer;
                
                if ($scenario === 0) {
                    $answers[$questionId] = $correctAnswer;
                } else {
                    $answers[$questionId] = $correctAnswer === 'true' ? 'false' : 'true';
                }

            } elseif ($type === 'essay') {
                // Essay answers
                $essayAnswers = [
                    "Đây là câu trả lời chi tiết của tôi. Tôi nghĩ rằng...",
                    "Theo quan điểm của em, vấn đề này có thể giải quyết bằng cách...",
                    "Em xin trình bày như sau: Thứ nhất... Thứ hai... Thứ ba...",
                ];
                $answers[$questionId] = $essayAnswers[$scenario];
            }
        }

        // Create submission
        $submission = ExamSubmission::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'attempt_number' => 1,
            'started_at' => now()->subMinutes(rand(30, 60)),
            'submitted_at' => now()->subMinutes(rand(1, 20)),
            'time_spent' => rand(20, 45),
            'answers' => $answers,
            'status' => 'submitted',
            'grading_status' => 'pending',
        ]);

        echo "  ✅ Created submission for: {$student->name}\n";
        echo "     - Scenario: " . ($scenario === 0 ? '💯 All correct' : ($scenario === 1 ? '📊 Some correct' : '❌ All wrong')) . "\n";
        echo "     - Answers: " . count($answers) . " questions\n";
    }

    DB::commit();

    echo "\n🎉 Test data created successfully!\n\n";
    echo "📌 Next steps:\n";
    echo "1. Go to: http://localhost:8000/teacher/grading\n";
    echo "2. You should see 3 submissions waiting to be graded\n";
    echo "3. Try 'Chấm tự động' for automatic grading\n";
    echo "4. Or click 'Xem' to manually grade essays\n\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
