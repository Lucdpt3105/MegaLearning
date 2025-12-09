<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\ClassRoom;
use App\Models\Subject;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🎯 Creating exams for testing...\n";

        // Get all classrooms
        $classrooms = ClassRoom::with('subject')->get();

        if ($classrooms->isEmpty()) {
            echo "⚠️  No classrooms found! Please run ClassRoomSeeder first.\n";
            return;
        }

        $examCount = 0;

        foreach ($classrooms as $classroom) {
            // Get teacher id from classroom
            $teacherId = $classroom->teacher_id ?? 1;
            
            // Get questions for this subject
            $questions = Question::where('subject_id', $classroom->subject_id)
                ->inRandomOrder()
                ->limit(10)
                ->get();

            if ($questions->isEmpty()) {
                echo "⚠️  No questions found for {$classroom->subject->name}. Skipping.\n";
                continue;
            }

            // Create 2 exams per classroom
            // Exam 1: Available now
            $exam1 = Exam::create([
                'title' => "Kiểm tra giữa kỳ - {$classroom->subject->name}",
                'subject_id' => $classroom->subject_id,
                'class_room_id' => $classroom->id,
                'duration' => 45,
                'total_points' => 10.0,
                'created_by' => $teacherId,
            ]);

            // Add questions to exam using DB insert
            $order = 1;
            foreach ($questions->take(5) as $question) {
                \DB::table('exam_questions')->insert([
                    'exam_id' => $exam1->id,
                    'question_id' => $question->id,
                    'points' => 2.0,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $examCount++;
            echo "✅ Created exam: {$exam1->title}\n";

            // Exam 2: Coming soon (future)
            $exam2 = Exam::create([
                'title' => "Kiểm tra cuối kỳ - {$classroom->subject->name}",
                'subject_id' => $classroom->subject_id,
                'class_room_id' => $classroom->id,
                'duration' => 90,
                'total_points' => 10.0,
                'created_by' => $teacherId,
            ]);

            // Add all questions to final exam
            $order = 1;
            foreach ($questions as $question) {
                \DB::table('exam_questions')->insert([
                    'exam_id' => $exam2->id,
                    'question_id' => $question->id,
                    'points' => 1.0,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $examCount++;
            echo "✅ Created exam: {$exam2->title}\n";
        }

        echo "\n🎉 Successfully created {$examCount} exams!\n";
        echo "📝 Test accounts:\n";
        echo "   Teacher: teacher@megalearning.local / password\n";
        echo "   Student: student1@megalearning.local / password\n";
        echo "\n💡 Students can now:\n";
        echo "   1. Login at /login\n";
        echo "   2. Go to /student/exams\n";
        echo "   3. Take available exams\n";
    }
}
