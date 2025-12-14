<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== KIỂM TRA ĐIỂM TRONG DATABASE ===\n\n";

$submissions = DB::table('exam_submissions')
    ->select('id', 'exam_id', 'score', 'grading_status')
    ->whereNotNull('score')
    ->get();

echo "Tổng số bài có điểm: {$submissions->count()}\n\n";

foreach ($submissions as $sub) {
    echo "ID: {$sub->id} | Exam: {$sub->exam_id} | Score: {$sub->score} | Status: {$sub->grading_status}\n";
}

echo "\n=== KIỂM TRA TOTAL_POINTS CỦA EXAMS ===\n\n";

$exams = DB::table('exams')
    ->select('id', 'title', 'total_points')
    ->get();

foreach ($exams as $exam) {
    echo "Exam ID: {$exam->id} | Title: {$exam->title} | Total Points: {$exam->total_points}\n";
}
