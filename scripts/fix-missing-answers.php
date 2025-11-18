<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Exam;

// Load environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Fixing questions with missing correct answers...\n\n";

$exam = Exam::first();
if (!$exam) {
    echo "❌ No exam found!\n";
    exit(1);
}

echo "📝 Exam: {$exam->title}\n\n";

$questions = $exam->questions()->get();

foreach ($questions as $index => $question) {
    $pivot = DB::table('exam_questions')
        ->where('exam_id', $exam->id)
        ->where('question_id', $question->id)
        ->first();

    $type = $pivot->custom_type ?? $question->type;
    $customAnswers = $pivot->custom_answers ? json_decode($pivot->custom_answers, true) : null;

    echo "Câu " . ($index + 1) . " (ID: {$question->id}, Type: {$type}):\n";

    // Check if already has correct answer
    if ($customAnswers && isset($customAnswers['correct_answer'])) {
        echo "  ✅ Already has correct answer: {$customAnswers['correct_answer']}\n";
        continue;
    }

    if ($question->correct_answer) {
        echo "  ✅ Already has correct answer: {$question->correct_answer}\n";
        continue;
    }

    // Need to add correct answer
    if ($type === 'multiple_choice') {
        // Add random correct answer A, B, C, or D
        $correctAnswer = ['A', 'B', 'C', 'D'][rand(0, 3)];
        
        $newCustomAnswers = [
            'correct_answer' => $correctAnswer,
            'option_a' => 'Đáp án A',
            'option_b' => 'Đáp án B',
            'option_c' => 'Đáp án C',
            'option_d' => 'Đáp án D',
        ];

        DB::table('exam_questions')
            ->where('exam_id', $exam->id)
            ->where('question_id', $question->id)
            ->update([
                'custom_answers' => json_encode($newCustomAnswers),
            ]);

        echo "  ✨ Added correct answer: {$correctAnswer}\n";

    } elseif ($type === 'true_false') {
        $correctAnswer = rand(0, 1) ? 'true' : 'false';
        
        $newCustomAnswers = [
            'correct_answer' => $correctAnswer,
        ];

        DB::table('exam_questions')
            ->where('exam_id', $exam->id)
            ->where('question_id', $question->id)
            ->update([
                'custom_answers' => json_encode($newCustomAnswers),
            ]);

        echo "  ✨ Added correct answer: {$correctAnswer}\n";

    } else {
        echo "  ⏭️ Skipping (essay/fill_blank type)\n";
    }
}

echo "\n✅ Done! Now run the create-proper-grading-data.php script again.\n";
