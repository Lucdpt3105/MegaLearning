<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Exam;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Checking pivot data structure...\n\n";

$exam = Exam::first();
$questions = $exam->questions()
    ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
    ->orderBy('exam_questions.order')
    ->get();

echo "Total questions: " . $questions->count() . "\n\n";

foreach ($questions->take(3) as $q) {
    echo "Question ID: {$q->id}\n";
    echo "Type: {$q->type}\n";
    echo "Pivot custom_type: " . ($q->pivot->custom_type ?? 'null') . "\n";
    echo "Pivot custom_answers type: " . gettype($q->pivot->custom_answers) . "\n";
    echo "Pivot custom_answers: ";
    var_dump($q->pivot->custom_answers);
    
    if (is_string($q->pivot->custom_answers)) {
        echo "Decoded custom_answers: ";
        var_dump(json_decode($q->pivot->custom_answers, true));
    }
    echo "\n";
}
