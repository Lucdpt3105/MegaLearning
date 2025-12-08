<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ExamSubmission;
use App\Models\Exam;

echo "=== KIỂM TRA LẠI BÀI NỘP ID 23 ===\n\n";

$submission = ExamSubmission::with('exam')->find(23);
$examId = $submission->exam_id;

// Load exam with relationships
$exam = Exam::with('questions.answers')->find($examId);

echo "Exam ID: {$exam->id}\n";
echo "Questions loaded: {$exam->questions->count()}\n\n";

// Test calculateScore manually
$answers = $submission->answers;

$totalPoints = 0;
$earnedPoints = 0;

echo "=== MANUAL CALCULATION ===\n";

foreach ($exam->questions as $question) {
    $points = $question->pivot->points ?? 1;
    
    if ($question->type === 'multiple_choice') {
        $totalPoints += $points;
        
        $studentAnswer = $answers[$question->id] ?? null;
        $correctAnswer = $question->answers->where('is_correct', true)->first();
        
        if ($correctAnswer && $studentAnswer && (string)$studentAnswer === (string)$correctAnswer->id) {
            $earnedPoints += $points;
        }
    }
}

echo "Total points possible: {$totalPoints}\n";
echo "Earned points: {$earnedPoints}\n";
echo "Score should be: " . round($earnedPoints, 2) . "\n";
echo "Score in DB: {$submission->score}\n";

// Now re-calculate and update
echo "\n=== RE-CALCULATING AND UPDATING ===\n";

$newScore = round($earnedPoints, 2);

$submission->score = $newScore;
$submission->save();

echo "✅ Updated submission score to: {$newScore}\n";

echo "\n";
