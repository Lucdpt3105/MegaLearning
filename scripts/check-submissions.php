<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\ExamSubmission;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📊 Latest Test Submissions:\n\n";

$submissions = ExamSubmission::with('student')
    ->latest()
    ->take(3)
    ->get();

foreach ($submissions as $sub) {
    echo "ID: {$sub->id}\n";
    echo "User: {$sub->student->name}\n";
    echo "Score: {$sub->score}\n";
    echo "Status: {$sub->grading_status}\n";
    echo "Answers count: " . count($sub->answers ?? []) . "\n";
    echo "---\n";
}
