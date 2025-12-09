<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Subject;

echo "=== Kiểm tra câu hỏi theo môn học ===\n\n";

$subjects = Subject::all();

foreach ($subjects as $subject) {
    echo "Môn: {$subject->name} (ID: {$subject->id})\n";
    
    $mcCount = Question::where('type', 'multiple_choice')
        ->where('subject_id', $subject->id)
        ->where('in_question_bank', true)
        ->count();
    
    echo "  MC: $mcCount câu";
    
    if ($mcCount > 0) {
        echo " (";
        foreach ([1, 2, 3, 4] as $level) {
            $count = Question::where('type', 'multiple_choice')
                ->where('subject_id', $subject->id)
                ->where('bloom_level', $level)
                ->where('in_question_bank', true)
                ->count();
            echo "L$level: $count, ";
        }
        echo ")";
    }
    
    echo "\n";
    
    $essayCount = Question::where('type', 'essay')
        ->where('subject_id', $subject->id)
        ->where('in_question_bank', true)
        ->count();
    
    echo "  Essay: $essayCount câu\n\n";
}
