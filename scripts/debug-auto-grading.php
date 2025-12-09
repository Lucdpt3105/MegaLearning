<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\Question;

echo "=== DEBUG CHẤM ĐIỂM TỰ ĐỘNG ===\n\n";

// Get a recent submission
$submission = ExamSubmission::with(['exam.questions.answers', 'student'])
    ->where('grading_status', 'auto_graded')
    ->orderBy('id', 'desc')
    ->first();

if (!$submission) {
    echo "Không tìm thấy bài nộp nào được chấm tự động.\n";
    exit;
}

echo "Bài nộp ID: {$submission->id}\n";
echo "Học sinh: {$submission->student->name}\n";
echo "Đề thi: {$submission->exam->title}\n";
echo "Điểm: {$submission->score}\n";
echo "Trạng thái: {$submission->grading_status}\n\n";

echo "=== CHI TIẾT CHẤM ĐIỂM ===\n";

$totalEarned = 0;
$totalPossible = 0;

foreach ($submission->exam->questions as $index => $question) {
    if ($question->type !== 'multiple_choice') {
        continue;
    }
    
    $points = $question->pivot->points ?? 1;
    $totalPossible += $points;
    
    $studentAnswerId = $submission->answers[$question->id] ?? null;
    $correctAnswer = $question->answers->where('is_correct', true)->first();
    
    echo "\nCâu " . ($index + 1) . ": {$question->content}\n";
    echo "Loại: {$question->type}, Điểm: {$points}\n";
    
    if ($correctAnswer) {
        echo "Đáp án đúng ID: {$correctAnswer->id} - {$correctAnswer->content}\n";
    } else {
        echo "⚠️  KHÔNG CÓ ĐÁP ÁN ĐÚNG!\n";
    }
    
    if ($studentAnswerId) {
        $studentAnswerObj = $question->answers->firstWhere('id', $studentAnswerId);
        echo "Học sinh chọn ID: {$studentAnswerId}";
        if ($studentAnswerObj) {
            echo " - {$studentAnswerObj->content}";
            echo " (is_correct: " . ($studentAnswerObj->is_correct ? 'true' : 'false') . ")";
        }
        echo "\n";
        
        // Check comparison
        $isCorrectStrict = $correctAnswer && (string)$studentAnswerId === (string)$correctAnswer->id;
        $isCorrectLoose = $correctAnswer && $studentAnswerId == $correctAnswer->id;
        
        echo "So sánh strict (===): " . ($isCorrectStrict ? 'ĐÚNG' : 'SAI') . "\n";
        echo "So sánh loose (==): " . ($isCorrectLoose ? 'ĐÚNG' : 'SAI') . "\n";
        
        if ($isCorrectStrict) {
            $totalEarned += $points;
            echo "✓ Được {$points} điểm\n";
        } else {
            echo "✗ Không được điểm\n";
        }
    } else {
        echo "Học sinh không trả lời\n";
        echo "✗ Không được điểm\n";
    }
}

echo "\n=== TỔNG KẾT ===\n";
echo "Tổng điểm kiếm được: {$totalEarned}\n";
echo "Tổng điểm có thể: {$totalPossible}\n";
echo "Điểm trong DB: {$submission->score}\n";

if ((float)$totalEarned !== (float)$submission->score) {
    echo "\n⚠️  CẢNH BÁO: Điểm tính toán ({$totalEarned}) KHÁC điểm trong DB ({$submission->score})!\n";
} else {
    echo "\n✅ Điểm khớp!\n";
}

echo "\n=== KIỂM TRA ANSWERS ARRAY ===\n";
echo "Kiểu dữ liệu: " . gettype($submission->answers) . "\n";
if (is_array($submission->answers)) {
    echo "Số câu trả lời: " . count($submission->answers) . "\n";
    echo "Mẫu 3 câu đầu:\n";
    $count = 0;
    foreach ($submission->answers as $qId => $aId) {
        if ($count >= 3) break;
        echo "  Question ID: {$qId} (type: " . gettype($qId) . ") => Answer ID: {$aId} (type: " . gettype($aId) . ")\n";
        $count++;
    }
}

echo "\n";
