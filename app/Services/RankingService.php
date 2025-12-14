<?php

namespace App\Services;

use App\Models\StudentRanking;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;
use App\Models\ExamSubmission;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingService
{
    /**
     * Tính toán xếp hạng cho tất cả học sinh
     */
    public function calculateAllRankings()
    {
        $classRooms = ClassRoom::with('subject')->get();
        $totalUpdated = 0;

        foreach ($classRooms as $classRoom) {
            $updated = $this->calculateClassRoomRanking($classRoom->id);
            $totalUpdated += $updated;
        }

        Log::info("Đã cập nhật xếp hạng cho {$totalUpdated} học sinh");
        
        return $totalUpdated;
    }

    /**
     * Tính toán xếp hạng cho một lớp học
     */
    public function calculateClassRoomRanking($classRoomId)
    {
        $classRoom = ClassRoom::with('subject')->findOrFail($classRoomId);

        // Lấy học sinh trong lớp
        $enrolledStudents = ClassEnrollment::where('class_room_id', $classRoom->id)
            ->where('status', 'active')
            ->pluck('student_id');

        if ($enrolledStudents->isEmpty()) {
            return 0;
        }

        $totalStudents = $enrolledStudents->count();
        $rankings = [];

        foreach ($enrolledStudents as $studentId) {
            $data = $this->calculateStudentMetrics($studentId, $classRoom);
            
            if ($data) {
                $rankings[] = array_merge($data, [
                    'student_id' => $studentId,
                    'class_room_id' => $classRoom->id,
                    'subject_id' => $classRoom->subject_id,
                    'total_students' => $totalStudents,
                    'calculated_at' => now(),
                ]);
            }
        }

        // Sắp xếp theo GPA giảm dần
        usort($rankings, function($a, $b) {
            return $b['gpa'] <=> $a['gpa'];
        });

        // Gán rank
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        // Cập nhật hoặc tạo mới
        foreach ($rankings as $ranking) {
            StudentRanking::updateOrCreate(
                [
                    'student_id' => $ranking['student_id'],
                    'class_room_id' => $ranking['class_room_id'],
                    'subject_id' => $ranking['subject_id'],
                ],
                $ranking
            );
        }

        return count($rankings);
    }

    /**
     * Tính toán các chỉ số của một học sinh
     */
    private function calculateStudentMetrics($studentId, $classRoom)
    {
        // Lấy tất cả bài thi của học sinh trong lớp/môn này
        $submissions = ExamSubmission::whereHas('exam', function($query) use ($classRoom) {
            $query->where('class_room_id', $classRoom->id)
                  ->orWhere('subject_id', $classRoom->subject_id);
        })
        ->where('student_id', $studentId)
        ->where('status', 'submitted')
        ->get();

        $totalExams = $submissions->count();
        
        if ($totalExams == 0) {
            return null;
        }

        // Tính điểm trung bình
        $gradedSubmissions = $submissions->where('grading_status', 'graded')
            ->whereNotNull('score');
        
        $averageScore = $gradedSubmissions->count() > 0 
            ? $gradedSubmissions->avg('score') 
            : 0;

        // Tính số bài đỗ (>= 5)
        $totalPassed = $gradedSubmissions->where('score', '>=', 5)->count();

        // Tính GPA (thang 4.0)
        $gpa = $this->calculateGPA($averageScore);

        // Tính tỷ lệ điểm danh
        $attendanceRate = $this->calculateAttendanceRate($studentId, $classRoom->id);

        return [
            'gpa' => $gpa,
            'average_score' => round($averageScore, 2),
            'total_exams_taken' => $totalExams,
            'total_exams_passed' => $totalPassed,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /**
     * Chuyển đổi điểm sang GPA (thang 4.0)
     */
    private function calculateGPA($averageScore)
    {
        if ($averageScore >= 9.0) {
            return 4.0;
        } elseif ($averageScore >= 8.5) {
            return 3.7;
        } elseif ($averageScore >= 8.0) {
            return 3.5;
        } elseif ($averageScore >= 7.0) {
            return 3.0;
        } elseif ($averageScore >= 6.5) {
            return 2.5;
        } elseif ($averageScore >= 5.5) {
            return 2.0;
        } elseif ($averageScore >= 5.0) {
            return 1.5;
        } elseif ($averageScore >= 4.0) {
            return 1.0;
        } else {
            return 0.0;
        }
    }

    /**
     * Tính tỷ lệ điểm danh
     */
    private function calculateAttendanceRate($studentId, $classRoomId)
    {
        // Kiểm tra xem có bảng attendance không
        if (!DB::getSchemaBuilder()->hasTable('attendances')) {
            return 100.0; // Default nếu chưa có bảng attendance
        }

        $totalSessions = Attendance::where('class_room_id', $classRoomId)->distinct('session_date')->count('session_date');
        
        if ($totalSessions == 0) {
            return 100.0;
        }

        $attendedSessions = Attendance::where('class_room_id', $classRoomId)
            ->where('student_id', $studentId)
            ->where('status', 'present')
            ->count();

        return round(($attendedSessions / $totalSessions) * 100, 2);
    }

    /**
     * Lấy top N học sinh xuất sắc
     */
    public function getTopStudents($limit = 10, $classRoomId = null, $subjectId = null)
    {
        $query = StudentRanking::with(['student', 'classRoom', 'subject'])
            ->orderByDesc('gpa')
            ->orderBy('rank');

        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Lấy xếp hạng của một học sinh cụ thể
     */
    public function getStudentRanking($studentId, $classRoomId = null, $subjectId = null)
    {
        $query = StudentRanking::where('student_id', $studentId);

        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->first();
    }
}
