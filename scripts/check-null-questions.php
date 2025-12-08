<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\Topic;

echo "=== KIỂM TRA CÂU HỎI BỊ NULL ===\n\n";

// Check questions with null content
$nullContent = Question::whereNull('content')->orWhere('content', '')->get();
echo "Câu hỏi có content NULL/rỗng: " . $nullContent->count() . "\n";

// Check questions with null subject
$nullSubject = Question::whereNull('subject_id')->get();
echo "Câu hỏi có subject_id NULL: " . $nullSubject->count() . "\n";

// Check questions with null topic
$nullTopic = Question::whereNull('topic_id')->get();
echo "Câu hỏi có topic_id NULL: " . $nullTopic->count() . "\n";

// Check questions with null difficulty
$nullDifficulty = Question::whereNull('difficulty')->get();
echo "Câu hỏi có difficulty NULL: " . $nullDifficulty->count() . "\n";

// Check questions without answers
echo "\n=== KIỂM TRA CÂU HỎI KHÔNG CÓ ĐÁP ÁN ===\n";
$questionsWithoutAnswers = Question::doesntHave('answers')->get();
echo "Câu hỏi không có đáp án: " . $questionsWithoutAnswers->count() . "\n";

// Check questions without correct answer
echo "\n=== KIỂM TRA CÂU HỎI KHÔNG CÓ ĐÁP ÁN ĐÚNG ===\n";
$questionsWithoutCorrectAnswer = Question::whereDoesntHave('answers', function($query) {
    $query->where('is_correct', true);
})->whereHas('answers')->get();
echo "Câu hỏi có đáp án nhưng không có đáp án đúng: " . $questionsWithoutCorrectAnswer->count() . "\n";

// Detailed list
if ($nullContent->count() > 0) {
    echo "\n=== CHI TIẾT CÂU HỎI NULL CONTENT ===\n";
    foreach ($nullContent as $q) {
        echo "ID: {$q->id}, Subject: {$q->subject_id}, Topic: {$q->topic_id}\n";
    }
}

if ($nullSubject->count() > 0) {
    echo "\n=== CHI TIẾT CÂU HỎI NULL SUBJECT ===\n";
    foreach ($nullSubject as $q) {
        echo "ID: {$q->id}, Content: " . substr($q->content ?? '', 0, 50) . "\n";
    }
}

if ($nullTopic->count() > 0) {
    echo "\n=== CHI TIẾT CÂU HỎI NULL TOPIC (10 đầu tiên) ===\n";
    foreach ($nullTopic->take(10) as $q) {
        $subject = Subject::find($q->subject_id);
        echo "ID: {$q->id}, Subject: " . ($subject ? $subject->name : 'N/A') . ", Content: " . substr($q->content ?? '', 0, 50) . "...\n";
    }
}

if ($questionsWithoutAnswers->count() > 0) {
    echo "\n=== CHI TIẾT CÂU HỎI KHÔNG CÓ ĐÁP ÁN (10 đầu tiên) ===\n";
    foreach ($questionsWithoutAnswers->take(10) as $q) {
        $subject = Subject::find($q->subject_id);
        echo "ID: {$q->id}, Subject: " . ($subject ? $subject->name : 'N/A') . ", Content: " . substr($q->content ?? '', 0, 50) . "...\n";
    }
}

if ($questionsWithoutCorrectAnswer->count() > 0) {
    echo "\n=== CHI TIẾT CÂU HỎI KHÔNG CÓ ĐÁP ÁN ĐÚNG (10 đầu tiên) ===\n";
    foreach ($questionsWithoutCorrectAnswer->take(10) as $q) {
        $subject = Subject::find($q->subject_id);
        $answerCount = $q->answers->count();
        echo "ID: {$q->id}, Subject: " . ($subject ? $subject->name : 'N/A') . ", Answers: {$answerCount}, Content: " . substr($q->content ?? '', 0, 50) . "...\n";
    }
}

// List all subjects and topics for reference
echo "\n=== DANH SÁCH MÔN HỌC ===\n";
$subjects = Subject::all();
foreach ($subjects as $subject) {
    echo "ID: {$subject->id} - {$subject->name}\n";
}

echo "\n=== DANH SÁCH CHỦ ĐỀ (theo môn) ===\n";
foreach ($subjects as $subject) {
    $topics = Topic::where('subject_id', $subject->id)->get();
    if ($topics->count() > 0) {
        echo "\n{$subject->name}:\n";
        foreach ($topics as $topic) {
            echo "  - ID: {$topic->id} - {$topic->name}\n";
        }
    }
}

echo "\n";
