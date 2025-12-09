<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

echo "=== BẮT ĐẦU SỬA CÂU HỎI VÀ ĐÁP ÁN ===\n\n";

DB::beginTransaction();

try {
    // Step 1: Create topics for each subject if they don't exist
    echo "Bước 1: Tạo chủ đề cho các môn học...\n";
    
    $subjects = Subject::all();
    $topicMapping = [];
    
    foreach ($subjects as $subject) {
        $topics = Topic::where('subject_id', $subject->id)->get();
        
        if ($topics->isEmpty()) {
            echo "  Tạo chủ đề cho môn {$subject->name}...\n";
            
            // Create default topics based on subject
            $defaultTopics = [];
            
            switch ($subject->name) {
                case 'Toán':
                    $defaultTopics = ['Đại số', 'Hình học', 'Giải tích', 'Xác suất thống kê'];
                    break;
                case 'Vật lý':
                    $defaultTopics = ['Cơ học', 'Nhiệt học', 'Điện từ học', 'Quang học'];
                    break;
                case 'Hóa học':
                    $defaultTopics = ['Hóa vô cơ', 'Hóa hữu cơ', 'Hóa phân tích', 'Hóa lý'];
                    break;
                case 'Lập trình Web':
                    $defaultTopics = ['HTML/CSS', 'JavaScript', 'Laravel', 'Database'];
                    break;
                case 'Giai tich':
                    $defaultTopics = ['Giới hạn', 'Đạo hàm', 'Tích phân', 'Chuỗi'];
                    break;
                default:
                    $defaultTopics = ['Chủ đề chung', 'Lý thuyết cơ bản', 'Bài tập nâng cao'];
            }
            
            foreach ($defaultTopics as $topicName) {
                $topic = Topic::create([
                    'name' => $topicName,
                    'subject_id' => $subject->id,
                    'description' => "Chủ đề {$topicName} thuộc môn {$subject->name}",
                ]);
                $topicMapping[$subject->id][] = $topic->id;
            }
        } else {
            $topicMapping[$subject->id] = $topics->pluck('id')->toArray();
        }
    }
    
    // Step 2: Update NULL topic_id for questions
    echo "\nBước 2: Gán topic_id cho các câu hỏi...\n";
    
    $nullTopicQuestions = Question::whereNull('topic_id')->get();
    $updated = 0;
    
    foreach ($nullTopicQuestions as $question) {
        if (isset($topicMapping[$question->subject_id])) {
            // Randomly assign a topic from available topics for this subject
            $availableTopics = $topicMapping[$question->subject_id];
            $randomTopic = $availableTopics[array_rand($availableTopics)];
            
            $question->topic_id = $randomTopic;
            $question->save();
            $updated++;
        }
    }
    
    echo "  Đã cập nhật {$updated} câu hỏi\n";
    
    // Step 3: Add answers for questions without answers
    echo "\nBước 3: Tạo đáp án cho câu hỏi không có đáp án...\n";
    
    $questionsWithoutAnswers = Question::doesntHave('answers')->get();
    $answersCreated = 0;
    
    foreach ($questionsWithoutAnswers as $question) {
        if ($question->type === 'multiple_choice') {
            // Create 4 answers, 1 correct
            $answers = [
                ['content' => 'Đáp án A (Đúng)', 'is_correct' => true],
                ['content' => 'Đáp án B', 'is_correct' => false],
                ['content' => 'Đáp án C', 'is_correct' => false],
                ['content' => 'Đáp án D', 'is_correct' => false],
            ];
            
            foreach ($answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $answerData['content'],
                    'is_correct' => $answerData['is_correct'],
                ]);
                $answersCreated++;
            }
        } elseif ($question->type === 'true_false') {
            // Create 2 answers
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đúng',
                'is_correct' => true,
            ]);
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Sai',
                'is_correct' => false,
            ]);
            $answersCreated += 2;
        } elseif ($question->type === 'essay') {
            // Essay questions don't need multiple choice answers
            // But create a sample correct answer for reference
            Answer::create([
                'question_id' => $question->id,
                'content' => 'Đáp án mẫu (cần chỉnh sửa)',
                'is_correct' => true,
            ]);
            $answersCreated++;
        }
    }
    
    echo "  Đã tạo {$answersCreated} đáp án cho " . $questionsWithoutAnswers->count() . " câu hỏi\n";
    
    // Step 4: Verify all questions now have correct answers
    echo "\nBước 4: Kiểm tra lại...\n";
    
    $stillNullTopic = Question::whereNull('topic_id')->count();
    
    echo "  Câu hỏi còn thiếu topic: {$stillNullTopic}\n";
    
    // Commit regardless, then check answers separately
    DB::commit();
    echo "\n✅ ĐÃ COMMIT! Kiểm tra tiếp...\n";
    
    $stillNoAnswers = Question::doesntHave('answers')->count();
    $stillNoCorrectAnswer = Question::whereDoesntHave('answers', function($query) {
        $query->where('is_correct', true);
    })->whereHas('answers')->count();
    
    echo "  Câu hỏi còn thiếu đáp án: {$stillNoAnswers}\n";
    echo "  Câu hỏi còn thiếu đáp án đúng: {$stillNoCorrectAnswer}\n";
    
} catch (\Exception $e) {
    DB::rollback();
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

// Final report
echo "\n=== BÁO CÁO CUỐI CÙNG ===\n";
echo "Tổng số câu hỏi: " . Question::count() . "\n";
echo "Câu hỏi trong question bank: " . Question::where('in_question_bank', true)->count() . "\n";
echo "Câu hỏi có đầy đủ thông tin: " . Question::whereNotNull('topic_id')
    ->whereHas('answers', function($query) {
        $query->where('is_correct', true);
    })->count() . "\n";

echo "\nCác chủ đề đã tạo:\n";
$allTopics = Topic::with('subject')->get();
foreach ($allTopics as $topic) {
    $questionCount = Question::where('topic_id', $topic->id)->count();
    echo "  - {$topic->name} ({$topic->subject->name}): {$questionCount} câu hỏi\n";
}

echo "\n";
