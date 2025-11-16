<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teachers
        $teachers = User::role('teacher')->get();

        if ($teachers->isEmpty()) {
            $this->command->info('No teachers found. Please create teachers first.');
            return;
        }

        // Get all students
        $students = User::role('student')->get();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please create students first.');
            return;
        }

        $classCount = 0;
        $enrollmentCount = 0;

        foreach ($teachers as $teacher) {
            // Get subjects for this teacher
            $subjects = Subject::where('teacher_id', $teacher->id)->get();

            if ($subjects->isEmpty()) {
                $this->command->info("Teacher {$teacher->name} has no subjects. Skipping.");
                continue;
            }

            foreach ($subjects as $subject) {
                // Create 1-2 classes per subject
                $numClasses = rand(1, 2);

                for ($i = 1; $i <= $numClasses; $i++) {
                    $classRoom = ClassRoom::create([
                        'name' => "{$subject->name} - Lớp " . chr(64 + $i), // A, B, C...
                        'code' => strtoupper($subject->code) . '-L' . $i,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                        'description' => "Lớp học môn {$subject->name} do giáo viên {$teacher->name} phụ trách",
                        'max_students' => rand(20, 40),
                        'status' => 'active',
                        'start_date' => now()->subDays(rand(1, 30)),
                        'end_date' => now()->addDays(rand(60, 120)),
                    ]);

                    $classCount++;

                    // Enroll random students (up to available students or 15, whichever is smaller)
                    if ($students->count() > 0) {
                        $numStudents = min(rand(3, 15), $students->count());
                        $enrolledStudents = $students->random($numStudents);

                        foreach ($enrolledStudents as $student) {
                            // Check if already enrolled to avoid duplicates
                            $exists = ClassEnrollment::where('class_room_id', $classRoom->id)
                                ->where('student_id', $student->id)
                                ->exists();
                            
                            if (!$exists) {
                                ClassEnrollment::create([
                                    'class_room_id' => $classRoom->id,
                                    'student_id' => $student->id,
                                    'status' => 'active',
                                    'enrolled_at' => now()->subDays(rand(1, 20)),
                                    'notes' => rand(0, 1) ? 'Học sinh ' . ['giỏi', 'khá', 'trung bình'][rand(0, 2)] : null,
                                ]);

                                $enrollmentCount++;
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("Created {$classCount} classes with {$enrollmentCount} student enrollments.");
    }
}
