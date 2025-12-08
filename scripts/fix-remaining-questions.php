<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;

echo "=== SỬA 10 CÂU HỎI CÒN LẠI ===\n\n";

DB::beginTransaction();

try {
    $questionsWithoutAnswers = Question::doesntHave('answers')->get();
    
    echo "Tìm thấy " . $questionsWithoutAnswers->count() . " câu hỏi cần sửa\n\n";
    
    foreach ($questionsWithoutAnswers as $question) {
        echo "Đang sửa câu hỏi ID: {$question->id} (Type: {$question->type})...\n";
        
        if ($question->type === 'multiple_choice') {
            // Create 4 answers, 1 correct
            $answers = [
                ['content' => 'Đáp án A (Đúng)', 'is_correct' => 1],
                ['content' => 'Đáp án B', 'is_correct' => 0],
                ['content' => 'Đáp án C', 'is_correct' => 0],
                ['content' => 'Đáp án D', 'is_correct' => 0],
            ];
            
            foreach ($answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $answerData['content'],
                    'is_correct' => $answerData['is_correct'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            echo "  ✓ Đã tạo 4 đáp án\n";
            
        } elseif ($question->type === 'true_false') {
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đúng',
                'is_correct' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Sai',
                'is_correct' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✓ Đã tạo 2 đáp án\n";
            
        } elseif ($question->type === 'essay') {
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đáp án mẫu (cần chỉnh sửa)',
                'is_correct' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✓ Đã tạo đáp án mẫu\n";
            
        } elseif ($question->type === 'fill_blank') {
            // For fill-in-the-blank, create a sample correct answer
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đáp án điền chỗ trống (cần chỉnh sửa)',
                'is_correct' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✓ Đã tạo đáp án điền chỗ trống\n";
            
        } else {
            // Default: create a generic answer
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đáp án cần chỉnh sửa',
                'is_correct' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✓ Đã tạo đáp án chung (type: {$question->type})\n";
        }
    }
    
    // Check again
    $stillNoAnswers = Question::doesntHave('answers')->count();
    
    if ($stillNoAnswers == 0) {
        DB::commit();
        echo "\n✅ HOÀN TẤT! Tất cả câu hỏi đã có đáp án.\n";
    } else {
        DB::commit(); // Still commit what we have
        echo "\n⚠️  Vẫn còn {$stillNoAnswers} câu hỏi chưa có đáp án.\n";
    }
    
} catch (\Exception $e) {
    DB::rollback();
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== KIỂM TRA CUỐI CÙNG ===\n";
echo "Tổng câu hỏi: " . Question::count() . "\n";
echo "Câu hỏi có đáp án: " . Question::has('answers')->count() . "\n";
echo "Câu hỏi không có đáp án: " . Question::doesntHave('answers')->count() . "\n";
echo "Câu hỏi có đáp án đúng: " . Question::whereHas('answers', function($q) { $q->where('is_correct', true); })->count() . "\n";

echo "\n";
