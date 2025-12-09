<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

echo "=== Test Auto-Grading Logic ===\n\n";

// Tìm hoặc tạo student
$student = User::first();
if (!$student) {
    echo "❌ Không tìm thấy user!\n";
    exit(1);
}

echo "📝 Student: {$student->name} (ID: {$student->id})\n\n";

// Tìm một exam có câu trắc nghiệm
$exam = Exam::with(['questions.answers'])
    ->whereHas('questions', function($q) {
        $q->where('type', 'multiple_choice');
    })
    ->first();

if (!$exam) {
    echo "❌ Không tìm thấy exam nào có câu trắc nghiệm!\n";
    exit(1);
}

echo "📋 Exam: {$exam->title} (ID: {$exam->id})\n";
echo "   Tổng điểm: {$exam->total_points}\n";
echo "   Số câu hỏi: {$exam->questions->count()}\n\n";

// Hiển thị các câu hỏi
echo "=== Danh sách câu hỏi ===\n";
foreach ($exam->questions as $index => $question) {
    $points = $question->pivot->points ?? 1;
    echo ($index + 1) . ". [{$question->type}] {$points} điểm\n";
    
    if ($question->type === 'multiple_choice') {
        foreach ($question->answers as $answer) {
            $mark = $answer->is_correct ? '✓' : ' ';
            echo "   [{$mark}] {$answer->id}: {$answer->content}\n";
        }
    }
}
echo "\n";

// Test Case 1: Trả lời TẤT CẢ đúng
echo "=== TEST CASE 1: Trả lời TẤT CẢ đúng ===\n";
$correctAnswers = [];
$expectedPoints = 0;

foreach ($exam->questions as $question) {
    if ($question->type === 'multiple_choice') {
        $correctAnswer = $question->answers->where('is_correct', true)->first();
        if ($correctAnswer) {
            $correctAnswers[$question->id] = $correctAnswer->id;
            $expectedPoints += $question->pivot->points ?? 1;
        }
    }
}

echo "Câu trả lời: " . json_encode($correctAnswers) . "\n";
echo "Điểm mong đợi: $expectedPoints\n";

$score1 = calculateScore($exam, $correctAnswers);
echo "Điểm thực tế: $score1\n";
echo $score1 == $expectedPoints ? "✅ PASS\n\n" : "❌ FAIL\n\n";

// Test Case 2: Trả lời MỘT NỬA đúng
echo "=== TEST CASE 2: Trả lời MỘT NỬA đúng ===\n";
$halfAnswers = [];
$halfExpectedPoints = 0;
$count = 0;
$total = count($correctAnswers);

foreach ($exam->questions as $question) {
    if ($question->type === 'multiple_choice') {
        $count++;
        if ($count <= ceil($total / 2)) {
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            if ($correctAnswer) {
                $halfAnswers[$question->id] = $correctAnswer->id;
                $halfExpectedPoints += $question->pivot->points ?? 1;
            }
        } else {
            // Chọn đáp án SAI
            $wrongAnswer = $question->answers->where('is_correct', false)->first();
            if ($wrongAnswer) {
                $halfAnswers[$question->id] = $wrongAnswer->id;
            }
        }
    }
}

echo "Số câu đúng: " . ceil($total / 2) . "/" . $total . "\n";
echo "Điểm mong đợi: $halfExpectedPoints\n";

$score2 = calculateScore($exam, $halfAnswers);
echo "Điểm thực tế: $score2\n";
echo $score2 == $halfExpectedPoints ? "✅ PASS\n\n" : "❌ FAIL\n\n";

// Test Case 3: Trả lời TẤT CẢ sai
echo "=== TEST CASE 3: Trả lời TẤT CẢ sai ===\n";
$wrongAnswers = [];

foreach ($exam->questions as $question) {
    if ($question->type === 'multiple_choice') {
        $wrongAnswer = $question->answers->where('is_correct', false)->first();
        if ($wrongAnswer) {
            $wrongAnswers[$question->id] = $wrongAnswer->id;
        }
    }
}

echo "Điểm mong đợi: 0\n";

$score3 = calculateScore($exam, $wrongAnswers);
echo "Điểm thực tế: $score3\n";
echo $score3 == 0 ? "✅ PASS\n\n" : "❌ FAIL\n\n";

// Test Case 4: Không trả lời
echo "=== TEST CASE 4: Không trả lời câu nào ===\n";
echo "Điểm mong đợi: 0\n";

$score4 = calculateScore($exam, []);
echo "Điểm thực tế: $score4\n";
echo $score4 == 0 ? "✅ PASS\n\n" : "❌ FAIL\n\n";

echo "=== HOÀN THÀNH ===\n";

// Helper function (giống logic trong controller)
function calculateScore($exam, $answers)
{
    $totalPoints = 0;
    $earnedPoints = 0;
    
    foreach ($exam->questions as $question) {
        $points = $question->pivot->points ?? 1;
        
        if ($question->type === 'multiple_choice') {
            $totalPoints += $points;
            
            $studentAnswer = $answers[$question->id] ?? null;
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            
            if ($correctAnswer && $studentAnswer && $studentAnswer == $correctAnswer->id) {
                $earnedPoints += $points;
            }
        }
    }
    
    if ($totalPoints == 0) {
        return 0;
    }
    
    return round($earnedPoints, 2);
}
