<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "📊 Database Summary:\n";
echo "==================\n\n";

echo "Subjects: " . App\Models\Subject::count() . "\n";
echo "Topics: " . App\Models\Topic::count() . "\n";
echo "Questions: " . App\Models\Question::count() . "\n";
echo "Questions with topics: " . App\Models\Question::whereNotNull('topic_id')->count() . "\n\n";

echo "📚 Sample Topics:\n";
echo "==================\n";
foreach (App\Models\Topic::with('subject')->take(15)->get() as $topic) {
    echo "  {$topic->subject->name} > {$topic->name}\n";
}

echo "\n❓ Sample Questions by Topic:\n";
echo "==================\n";
$topics = App\Models\Topic::with(['questions' => function($q) {
    $q->take(2);
}])->take(5)->get();

foreach ($topics as $topic) {
    echo "\n📌 {$topic->name}:\n";
    foreach ($topic->questions as $question) {
        echo "   - " . substr($question->question_text, 0, 60) . "...\n";
    }
}

echo "\n✅ Database is ready for search functionality!\n";
