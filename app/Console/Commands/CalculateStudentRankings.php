<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentRanking;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\ExamSubmission;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

/**
 * UC-SYS-004: Thống kê điểm số và xếp hạng
 * Batch job tự động tính toán GPA và xếp hạng
 */
class CalculateStudentRankings extends Command
{
    protected $signature = 'rankings:calculate {--class_room_id=} {--student_id=}';
    
    protected $description = 'UC-SYS-004: Tính toán GPA và xếp hạng học sinh tự động';

    public function handle()
    {
        $this->info('🚀 Bắt đầu tính toán xếp hạng học sinh...');

        $classRoomId = $this->option('class_room_id');
        $studentId = $this->option('student_id');

        if ($studentId) {
            $this->calculateForStudent($studentId);
        } elseif ($classRoomId) {
            $this->calculateForClassRoom($classRoomId);
        } else {
            $this->calculateForAllClassRooms();
        }

        $this->info('✅ Hoàn thành tính toán xếp hạng!');
    }

    /**
     * Calculate rankings for all class rooms
     */
    private function calculateForAllClassRooms()
    {
        $classRooms = ClassRoom::all();
        
        $bar = $this->output->createProgressBar($classRooms->count());
        $bar->start();

        foreach ($classRooms as $classRoom) {
            $this->calculateForClassRoom($classRoom->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Calculate rankings for a specific class room
     */
    private function calculateForClassRoom($classRoomId)
    {
        $classRoom = ClassRoom::with('enrollments.student')->find($classRoomId);
        
        if (!$classRoom) {
            $this->error("Lớp học #{$classRoomId} không tồn tại");
            return;
        }

        $students = $classRoom->enrollments->pluck('student');
        $totalStudents = $students->count();

        if ($totalStudents == 0) {
            $this->warn("Lớp {$classRoom->name} không có học sinh nào");
            return;
        }

        // Calculate for each student
        $rankings = [];
        foreach ($students as $student) {
            $stats = $this->calculateStudentStats($student->id, $classRoom);
            
            if ($stats) {
                $rankings[] = [
                    'student_id' => $student->id,
                    'class_room_id' => $classRoom->id,
                    'subject_id' => $classRoom->subject_id,
                    'gpa' => $stats['gpa'],
                    'rank' => 0, // Will be calculated later
                    'total_students' => $totalStudents,
                    'average_score' => $stats['average_score'],
                    'total_exams_taken' => $stats['total_exams_taken'],
                    'total_exams_passed' => $stats['total_exams_passed'],
                    'attendance_rate' => $stats['attendance_rate'],
                    'calculated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Sort by GPA descending
        usort($rankings, function($a, $b) {
            return $b['gpa'] <=> $a['gpa'];
        });

        // Assign ranks
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        // Delete old rankings for this class
        StudentRanking::where('class_room_id', $classRoom->id)->delete();

        // Insert new rankings
        StudentRanking::insert($rankings);

        $this->info("✓ Đã tính xếp hạng cho lớp {$classRoom->name}: {$totalStudents} học sinh");
    }

    /**
     * Calculate rankings for a specific student
     */
    private function calculateForStudent($studentId)
    {
        $student = User::role('student')->find($studentId);
        
        if (!$student) {
            $this->error("Học sinh #{$studentId} không tồn tại");
            return;
        }

        // Get all class rooms the student is enrolled in
        $classRooms = ClassRoom::whereHas('enrollments', function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->get();

        foreach ($classRooms as $classRoom) {
            $this->calculateForClassRoom($classRoom->id);
        }
    }

    /**
     * Calculate statistics for a student in a specific class
     */
    private function calculateStudentStats($studentId, $classRoom)
    {
        // Get all exam submissions for this student in this class
        $submissions = ExamSubmission::where('student_id', $studentId)
            ->whereHas('exam', function($query) use ($classRoom) {
                $query->where('subject_id', $classRoom->subject_id);
            })
            ->where('grading_status', 'completed')
            ->get();

        if ($submissions->isEmpty()) {
            return null;
        }

        $totalScore = 0;
        $totalExams = $submissions->count();
        $passedExams = 0;

        foreach ($submissions as $submission) {
            $score = $submission->total_score ?? 0;
            $totalScore += $score;
            
            // Assume passing score is 5.0 or above (can be configurable)
            if ($score >= 5.0) {
                $passedExams++;
            }
        }

        $averageScore = $totalExams > 0 ? $totalScore / $totalExams : 0;
        
        // GPA calculation (scale 0-4.0)
        // Convert 0-10 scale to 0-4.0 GPA
        $gpa = ($averageScore / 10) * 4;

        // Calculate attendance rate
        $totalAttendance = Attendance::where('class_room_id', $classRoom->id)->count();
        $studentAttendance = Attendance::where('class_room_id', $classRoom->id)
            ->where('student_id', $studentId)
            ->where('status', 'present')
            ->count();

        $attendanceRate = $totalAttendance > 0 
            ? ($studentAttendance / $totalAttendance) * 100 
            : 0;

        return [
            'gpa' => round($gpa, 2),
            'average_score' => round($averageScore, 2),
            'total_exams_taken' => $totalExams,
            'total_exams_passed' => $passedExams,
            'attendance_rate' => round($attendanceRate, 2),
        ];
    }
}
