<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Subject;
use App\Models\User;

echo "=== Thêm câu hỏi vào database ===\n\n";

// Lấy user đầu tiên làm created_by
$teacher = User::first();

if (!$teacher) {
    echo "Không tìm thấy user nào trong database!\n";
    exit(1);
}

echo "Sử dụng user: {$teacher->name} (ID: {$teacher->id})\n\n";

$subjects = Subject::all();

foreach ($subjects as $subject) {
    echo "Môn: {$subject->name} (ID: {$subject->id})\n";
    
    // Đếm số câu hiện tại
    $currentMC = Question::where('subject_id', $subject->id)
        ->where('type', 'multiple_choice')
        ->where('in_question_bank', true)
        ->count();
    
    $currentEssay = Question::where('subject_id', $subject->id)
        ->where('type', 'essay')
        ->where('in_question_bank', true)
        ->count();
    
    echo "  Hiện có: MC=$currentMC, Essay=$currentEssay\n";
    
    // Thêm 40 câu trắc nghiệm (10 câu mỗi level)
    $mcAdded = 0;
    foreach ([1, 2, 3, 4] as $level) {
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'topic_id' => null,
                'content' => "Câu hỏi trắc nghiệm {$subject->name} - Level $level - Câu $i",
                'type' => 'multiple_choice',
                'bloom_level' => $level,
                'difficulty' => $level <= 2 ? 'easy' : ($level == 3 ? 'medium' : 'hard'),
                'points' => 1.0,
                'in_question_bank' => true,
                'options' => json_encode([
                    ['text' => 'Đáp án A', 'is_correct' => true],
                    ['text' => 'Đáp án B', 'is_correct' => false],
                    ['text' => 'Đáp án C', 'is_correct' => false],
                    ['text' => 'Đáp án D', 'is_correct' => false],
                ]),
                'correct_answer' => 'A',
                'created_by' => $teacher->id,
            ]);
            $mcAdded++;
        }
    }
    
    // Thêm 20 câu tự luận (5 câu mỗi level)
    $essayAdded = 0;
    foreach ([1, 2, 3, 4] as $level) {
        for ($i = 1; $i <= 5; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'topic_id' => null,
                'content' => "Câu hỏi tự luận {$subject->name} - Level $level - Câu $i\n\nHãy trình bày chi tiết câu trả lời của bạn.",
                'type' => 'essay',
                'bloom_level' => $level,
                'difficulty' => $level <= 2 ? 'easy' : ($level == 3 ? 'medium' : 'hard'),
                'points' => 2.0,
                'in_question_bank' => true,
                'options' => null,
                'correct_answer' => "Đáp án mẫu cho câu hỏi tự luận {$subject->name} Level $level",
                'created_by' => $teacher->id,
            ]);
            $essayAdded++;
        }
    }
    
    echo "  Đã thêm: MC=$mcAdded, Essay=$essayAdded\n";
    
    // Đếm lại
    $newMC = Question::where('subject_id', $subject->id)
        ->where('type', 'multiple_choice')
        ->where('in_question_bank', true)
        ->count();
    
    $newEssay = Question::where('subject_id', $subject->id)
        ->where('type', 'essay')
        ->where('in_question_bank', true)
        ->count();
    
    echo "  Tổng mới: MC=$newMC, Essay=$newEssay\n\n";
}

echo "=== HOÀN THÀNH ===\n";
echo "Đã thêm 40 câu MC và 20 câu Essay cho mỗi môn học!\n";
