<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

$generic = Question::where('content', 'LIKE', 'Câu hỏi %')->count();
$real = Question::where('content', 'NOT LIKE', 'Câu hỏi %')->count();

echo "Câu hỏi dạng generic (Câu hỏi...): {$generic}\n";
echo "Câu hỏi có nội dung thật: {$real}\n";

if ($generic > 0) {
    echo "\nMẫu câu hỏi generic:\n";
    $samples = Question::where('content', 'LIKE', 'Câu hỏi %')->take(3)->get();
    foreach ($samples as $q) {
        echo "  ID {$q->id}: " . substr($q->content, 0, 80) . "\n";
    }
}

echo "\n";
