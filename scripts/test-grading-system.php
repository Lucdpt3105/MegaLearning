<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Exam;
use App\Models\Question;

echo "=== KIỂM TRA HỆ THỐNG CHẤM ĐIỂM ===\n\n";

// Check a sample exam
$exam = Exam::with('questions.answers')->first();

if (!$exam) {
    echo "❌ Không tìm thấy đề thi nào!\n";
    exit;
}

echo "Đề thi: {$exam->title}\n";
echo "Loại: {$exam->type}\n";
echo "Số câu hỏi: {$exam->questions->count()}\n\n";

echo "=== KIỂM TRA CÂU HỎI ===\n";

$mcCount = 0;
$noAnswerCount = 0;
$noCorrectCount = 0;

foreach ($exam->questions as $q) {
    if ($q->type === 'multiple_choice') {
        $mcCount++;
        
        if ($q->answers->isEmpty()) {
            $noAnswerCount++;
            echo "⚠️  Câu {$q->id}: KHÔNG CÓ ĐÁP ÁN!\n";
        } else {
            $correct = $q->answers->where('is_correct', true)->first();
            if (!$correct) {
                $noCorrectCount++;
                echo "⚠️  Câu {$q->id}: KHÔNG CÓ ĐÁP ÁN ĐÚNG!\n";
            }
        }
    }
}

echo "\n=== THỐNG KÊ ===\n";
echo "Câu trắc nghiệm: {$mcCount}\n";
echo "Câu thiếu đáp án: {$noAnswerCount}\n";
echo "Câu thiếu đáp án đúng: {$noCorrectCount}\n";

if ($noAnswerCount > 0 || $noCorrectCount > 0) {
    echo "\n⚠️  CÓ VẤN ĐỀ VỚI DỮ LIỆU CÂU HỎI!\n";
} else {
    echo "\n✅ DỮ LIỆU CÂU HỎI OK!\n";
}

// Test một câu hỏi cụ thể
echo "\n=== TEST 1 CÂU HỎI MẪU ===\n";
$sampleQ = $exam->questions->where('type', 'multiple_choice')->first();

if ($sampleQ) {
    echo "Câu hỏi: {$sampleQ->content}\n";
    echo "Số đáp án: {$sampleQ->answers->count()}\n";
    
    foreach ($sampleQ->answers as $a) {
        $mark = $a->is_correct ? ' ✅' : '';
        echo "  - ID {$a->id}: {$a->content}{$mark}\n";
    }
    
    $correct = $sampleQ->answers->where('is_correct', true)->first();
    if ($correct) {
        echo "\nĐáp án đúng ID: {$correct->id}\n";
        
        // Test comparison
        $testId = (string)$correct->id;
        $compareStrict = $testId === (string)$correct->id;
        echo "So sánh strict: " . ($compareStrict ? '✅ OK' : '❌ FAIL') . "\n";
    }
}

echo "\n";
