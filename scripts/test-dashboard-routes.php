<?php

/**
 * Test Dashboard Routes
 * This script verifies all routes used in the teacher dashboard exist
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$routes = [
    'teacher.subjects.index',
    'teacher.topics',
    'teacher.questions.index',
    'teacher.exams.index',
    'teacher.video-calls.index',
    'teacher.grading.index',
    'teacher.students',
    'teacher.documents.index',
];

echo "Testing Dashboard Routes:\n";
echo str_repeat("=", 60) . "\n\n";

$passed = 0;
$failed = 0;

foreach ($routes as $routeName) {
    try {
        $url = route($routeName);
        echo "✅ {$routeName} → {$url}\n";
        $passed++;
    } catch (\Exception $e) {
        echo "❌ {$routeName} → NOT FOUND\n";
        $failed++;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Results: {$passed} passed, {$failed} failed\n";

if ($failed > 0) {
    exit(1);
}

echo "✅ All dashboard routes are working!\n";
exit(0);
