<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

echo "=== KIỂM TRA 10 CÂU HỎI THIẾU ĐÁP ÁN ===\n\n";

$questionsWithoutAnswers = Question::doesntHave('answers')->get();

foreach ($questionsWithoutAnswers as $q) {
    echo "ID: {$q->id}\n";
    echo "Type: {$q->type}\n";
    echo "Subject: {$q->subject_id}\n";
    echo "Topic: {$q->topic_id}\n";
    echo "Content: " . substr($q->content, 0, 100) . "...\n";
    echo "---\n";
}

echo "\n";
