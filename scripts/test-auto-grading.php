<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\ExamSubmission;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🤖 Testing auto-grading...\n\n";

// Get the 3 test submissions
$submissions = ExamSubmission::whereIn('id', [10, 11, 12])
    ->with(['student', 'exam'])
    ->get();

foreach ($submissions as $submission) {
    echo "Testing: {$submission->student->name}\n";
    echo "Exam: {$submission->exam->title}\n";
    echo "Before - Score: {$submission->score}, Status: {$submission->grading_status}\n";
    
    // Get exam questions
    $examQuestions = $submission->exam->questions()
        ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
        ->get();

    $studentAnswers = $submission->answers ?? [];
    $totalScore = 0;
    $hasEssay = false;
    $correctCount = 0;
    $totalMC = 0;

    foreach ($examQuestions as $question) {
        $questionId = $question->id;
        $points = $question->pivot->points;
        $type = $question->pivot->custom_type ?? $question->type;

        if (in_array($type, ['multiple_choice', 'true_false'])) {
            $totalMC++;
            if (isset($studentAnswers[$questionId])) {
                // Decode custom_answers if it's a JSON string
                $customAnswers = $question->pivot->custom_answers;
                if (is_string($customAnswers)) {
                    $customAnswers = json_decode($customAnswers, true);
                }
                $correctAnswer = ($customAnswers['correct_answer'] ?? null) ?? $question->correct_answer;
                
                if ($studentAnswers[$questionId] == $correctAnswer) {
                    $totalScore += $points;
                    $correctCount++;
                }
            }
        } else {
            $hasEssay = true;
        }
    }

    echo "MC Questions: {$totalMC}, Correct: {$correctCount}\n";
    echo "Calculated Score: {$totalScore}\n";
    echo "Has Essay: " . ($hasEssay ? 'Yes' : 'No') . "\n";
    
    // Update the submission
    $submission->update([
        'score' => $totalScore,
        'grading_status' => $hasEssay ? 'partially_graded' : 'auto_graded',
        'graded_at' => now(),
    ]);
    
    echo "After - Score: {$submission->fresh()->score}, Status: {$submission->fresh()->grading_status}\n";
    echo "---\n\n";
}

echo "✅ Auto-grading test complete!\n";
