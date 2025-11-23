<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamSubmission;

// Load environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Debugging auto-grading issue...\n\n";

// Get first submission
$submission = ExamSubmission::with(['exam.questions'])->first();

if (!$submission) {
    echo "❌ No submission found!\n";
    exit(1);
}

echo "📋 Submission ID: {$submission->id}\n";
echo "👤 Student: {$submission->student->name}\n";
echo "📝 Exam: {$submission->exam->title}\n\n";

echo "🎯 Exam Questions:\n";
echo "─────────────────────────────────────────────────────────\n";

$examQuestions = $submission->exam->questions()
    ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
    ->orderBy('exam_questions.order')
    ->get();

$studentAnswers = $submission->answers ?? [];

foreach ($examQuestions as $index => $question) {
    $questionId = $question->id;
    $type = $question->pivot->custom_type ?? $question->type;
    $points = $question->pivot->points;
    
    echo "\nCâu " . ($index + 1) . " (ID: {$questionId}):\n";
    echo "  Type: {$type}\n";
    echo "  Points: {$points}\n";
    
    if ($type === 'multiple_choice' || $type === 'true_false') {
        // Check custom answers first
        if ($question->pivot->custom_answers) {
            $customAnswers = $question->pivot->custom_answers;
            $correctAnswer = $customAnswers['correct_answer'] ?? null;
            echo "  Correct Answer (custom): {$correctAnswer}\n";
        } else {
            $correctAnswer = $question->correct_answer;
            echo "  Correct Answer (question): {$correctAnswer}\n";
        }
        
        $studentAnswer = $studentAnswers[$questionId] ?? 'NO ANSWER';
        echo "  Student Answer: {$studentAnswer}\n";
        
        if ($studentAnswer !== 'NO ANSWER') {
            $isCorrect = $studentAnswer == $correctAnswer;
            echo "  Result: " . ($isCorrect ? "✅ CORRECT (+{$points})" : "❌ WRONG (+0)") . "\n";
        }
    } else {
        echo "  Essay - needs manual grading\n";
    }
}

echo "\n─────────────────────────────────────────────────────────\n";
echo "\n📊 Student Answers Array:\n";
echo json_encode($studentAnswers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

echo "\n🔧 Checking if answers match question IDs...\n";
foreach ($studentAnswers as $answerQuestionId => $answer) {
    $exists = $examQuestions->firstWhere('id', $answerQuestionId);
    if (!$exists) {
        echo "  ⚠️ WARNING: Answer for question ID {$answerQuestionId} not found in exam!\n";
    } else {
        echo "  ✅ Question ID {$answerQuestionId} exists\n";
    }
}
