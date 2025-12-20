<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ExamSubmission;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

echo "=== CHUYỂN ĐỔI ĐIỂM SANG THANG 10 ===\n\n";

// Get all submissions with scores
$submissions = ExamSubmission::whereNotNull('score')
    ->with('exam')
    ->get();

echo "Tìm thấy {$submissions->count()} bài nộp có điểm.\n\n";

$converted = 0;
$skipped = 0;

DB::beginTransaction();

try {
    foreach ($submissions as $submission) {
        $exam = $submission->exam;
        
        if (!$exam) {
            echo "⚠️  Submission {$submission->id}: Không tìm thấy đề thi!\n";
            $skipped++;
            continue;
        }
        
        $totalPoints = $exam->total_points;
        $currentScore = $submission->score;
        
        // Skip if already on scale of 10 (score <= 10)
        if ($currentScore <= 10) {
            $skipped++;
            continue;
        }
        
        // Convert to scale of 10
        if ($totalPoints > 0) {
            $newScore = round(($currentScore / $totalPoints) * 10, 2);
            
            $submission->score = $newScore;
            $submission->save();
            
            echo "✅ Submission {$submission->id}: {$currentScore}/{$totalPoints} → {$newScore}/10\n";
            $converted++;
        } else {
            echo "⚠️  Submission {$submission->id}: total_points = 0, bỏ qua\n";
            $skipped++;
        }
    }
    
    DB::commit();
    
    echo "\n=== KẾT QUẢ ===\n";
    echo "Đã chuyển đổi: {$converted} bài\n";
    echo "Đã bỏ qua: {$skipped} bài\n";
    echo "\n✅ Hoàn tất!\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
