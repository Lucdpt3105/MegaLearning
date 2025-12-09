<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ExamSubmission;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

echo "=== SỬA TẤT CẢ BÀI NỘP BỊ CHẤM SAI ===\n\n";

// Get all auto-graded submissions
$submissions = ExamSubmission::where('grading_status', 'auto_graded')
    ->with('exam')
    ->get();

echo "Tìm thấy {$submissions->count()} bài nộp đã chấm tự động.\n\n";

$fixed = 0;
$unchanged = 0;

foreach ($submissions as $submission) {
    $exam = Exam::with('questions.answers')->find($submission->exam_id);
    
    if (!$exam) {
        echo "⚠️  Submission {$submission->id}: Không tìm thấy đề thi!\n";
        continue;
    }
    
    $answers = $submission->answers;
    
    // Recalculate score
    $totalPoints = 0;
    $earnedPoints = 0;
    
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
    
    $newScore = round($earnedPoints, 2);
    $oldScore = $submission->score ?? 0;
    
    if ((float)$newScore !== (float)$oldScore) {
        echo "Submission {$submission->id} ({$submission->student->name} - {$exam->title}): ";
        echo "Old: {$oldScore} → New: {$newScore}\n";
        
        $submission->score = $newScore;
        $submission->save();
        $fixed++;
    } else {
        $unchanged++;
    }
}

echo "\n=== KẾT QUẢ ===\n";
echo "✅ Đã sửa: {$fixed} bài nộp\n";
echo "📝 Không thay đổi: {$unchanged} bài nộp\n";

echo "\n";
