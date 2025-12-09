<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

echo "=== Kiểm tra số lượng câu hỏi trong Question Bank ===\n\n";

echo "Câu trắc nghiệm (Multiple Choice):\n";
foreach ([1, 2, 3, 4] as $level) {
    $count = Question::where('type', 'multiple_choice')
        ->where('bloom_level', $level)
        ->where('in_question_bank', true)
        ->count();
    echo "  Level $level: $count câu\n";
}

$totalMC = Question::where('type', 'multiple_choice')
    ->where('in_question_bank', true)
    ->count();
echo "  TỔNG MC: $totalMC câu\n\n";

echo "Câu tự luận (Essay):\n";
foreach ([1, 2, 3, 4] as $level) {
    $count = Question::where('type', 'essay')
        ->where('bloom_level', $level)
        ->where('in_question_bank', true)
        ->count();
    echo "  Level $level: $count câu\n";
}

$totalEssay = Question::where('type', 'essay')
    ->where('in_question_bank', true)
    ->count();
echo "  TỔNG Essay: $totalEssay câu\n\n";

echo "TỔNG TẤT CẢ: " . ($totalMC + $totalEssay) . " câu\n";
