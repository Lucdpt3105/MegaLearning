<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Subject;

echo "=== DEBUG QUESTION BANK ===\n\n";

// Check total questions
$totalQuestions = Question::count();
echo "Tổng số câu hỏi trong database: $totalQuestions\n";

// Check questions in bank
$inBank = Question::where('in_question_bank', true)->count();
echo "Câu hỏi có in_question_bank = true: $inBank\n";

// Check questions NOT in bank
$notInBank = Question::where('in_question_bank', false)->count();
echo "Câu hỏi có in_question_bank = false: $notInBank\n";

// Check questions with NULL
$nullBank = Question::whereNull('in_question_bank')->count();
echo "Câu hỏi có in_question_bank = NULL: $nullBank\n\n";

// List questions by subject
echo "=== CÂU HỎI THEO MÔN HỌC ===\n";
$subjects = Subject::all();
foreach ($subjects as $subject) {
    $count = Question::where('subject_id', $subject->id)
        ->where('in_question_bank', true)
        ->count();
    echo "- {$subject->name}: $count câu hỏi\n";
}

echo "\n=== MẪU 5 CÂU HỎI ĐẦU TIÊN ===\n";
$samples = Question::take(5)->get(['id', 'content', 'subject_id', 'in_question_bank', 'type']);
foreach ($samples as $q) {
    $subject = Subject::find($q->subject_id);
    echo "\nID: {$q->id}\n";
    echo "Môn: " . ($subject ? $subject->name : 'N/A') . "\n";
    echo "Loại: {$q->type}\n";
    echo "In Bank: " . ($q->in_question_bank ? 'true' : 'false') . "\n";
    echo "Nội dung: " . substr($q->content, 0, 100) . "...\n";
}

echo "\n=== KIẾN NGHỊ ===\n";
if ($inBank == 0 && $totalQuestions > 0) {
    echo "⚠️  CÓ CÂU HỎI NHƯNG CHƯA SET in_question_bank = true\n";
    echo "Chạy lệnh sau để fix:\n";
    echo "UPDATE questions SET in_question_bank = 1 WHERE in_question_bank IS NULL OR in_question_bank = 0;\n";
}

echo "\n";
