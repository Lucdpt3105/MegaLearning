<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Exam;

echo "=== TEST CALCULATE SCORE FUNCTION ===\n\n";

// Get an exam
$exam = Exam::with('questions.answers')->first();

if (!$exam) {
    echo "Không tìm thấy đề thi nào.\n";
    exit;
}

echo "Đề thi: {$exam->title}\n";
echo "Số câu hỏi: {$exam->questions->count()}\n";

// Simulate correct answers
$correctAnswers = [];
foreach ($exam->questions as $q) {
    if ($q->type === 'multiple_choice') {
        $correct = $q->answers->where('is_correct', true)->first();
        if ($correct) {
            $correctAnswers[$q->id] = $correct->id;
        }
    }
}

echo "Số câu trắc nghiệm: " . count($correctAnswers) . "\n";

// Test the calculation
echo "\n=== TESTING WITH CORRECT ANSWERS ===\n";

$totalPoints = 0;
$earnedPoints = 0;

foreach ($exam->questions as $question) {
    $points = $question->pivot->points ?? 1;
    
    if ($question->type === 'multiple_choice') {
        $totalPoints += $points;
        
        echo "\nQuestion ID: {$question->id}\n";
        echo "Type: {$question->type}\n";
        echo "Points: {$points}\n";
        echo "Answers loaded: " . $question->answers->count() . "\n";
        
        $studentAnswer = $correctAnswers[$question->id] ?? null;
        $correctAnswer = $question->answers->where('is_correct', true)->first();
        
        if ($correctAnswer) {
            echo "Correct answer ID: {$correctAnswer->id}\n";
        } else {
            echo "⚠️  NO CORRECT ANSWER!\n";
        }
        
        if ($studentAnswer) {
            echo "Student answer ID: {$studentAnswer}\n";
        }
        
        if ($correctAnswer && $studentAnswer && (string)$studentAnswer === (string)$correctAnswer->id) {
            $earnedPoints += $points;
            echo "✓ Earned {$points} points\n";
        } else {
            echo "✗ No points\n";
        }
    }
}

echo "\n=== RESULT ===\n";
echo "Total possible: {$totalPoints}\n";
echo "Total earned: {$earnedPoints}\n";
echo "Final score: " . round($earnedPoints, 2) . "\n";

echo "\n";
