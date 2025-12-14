<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\ExamSubmission;
use App\Models\StudentRanking;
use App\Models\ClassEnrollment;
use Illuminate\Support\Facades\DB;

class StudentRankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        StudentRanking::truncate();

        // Lấy tất cả học sinh
        $students = User::role('student')->get();

        // Lấy tất cả lớp học
        $classRooms = ClassRoom::with('subject')->get();

        foreach ($classRooms as $classRoom) {
            // Lấy học sinh trong lớp
            $enrolledStudents = ClassEnrollment::where('class_room_id', $classRoom->id)
                ->where('status', 'active')
                ->pluck('student_id');

            if ($enrolledStudents->isEmpty()) {
                continue;
            }

            $totalStudents = $enrolledStudents->count();
            $rankings = [];

            foreach ($enrolledStudents as $studentId) {
                // Lấy tất cả bài thi của học sinh trong lớp này
                $submissions = ExamSubmission::whereHas('exam', function($query) use ($classRoom) {
                    $query->where('class_room_id', $classRoom->id)
                          ->orWhere('subject_id', $classRoom->subject_id);
                })
                ->where('student_id', $studentId)
                ->where('status', 'submitted')
                ->get();

                $totalExams = $submissions->count();
                
                if ($totalExams == 0) {
                    continue;
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

                // Tính tỷ lệ điểm danh (giả sử)
                $attendanceRate = rand(70, 100);

                $rankings[] = [
                    'student_id' => $studentId,
                    'class_room_id' => $classRoom->id,
                    'subject_id' => $classRoom->subject_id,
                    'gpa' => $gpa,
                    'total_students' => $totalStudents,
                    'average_score' => $averageScore,
                    'total_exams_taken' => $totalExams,
                    'total_exams_passed' => $totalPassed,
                    'attendance_rate' => $attendanceRate,
                    'calculated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Sắp xếp theo GPA giảm dần và gán rank
            usort($rankings, function($a, $b) {
                return $b['gpa'] <=> $a['gpa'];
            });

            foreach ($rankings as $index => &$ranking) {
                $ranking['rank'] = $index + 1;
            }

            // Insert vào database
            if (!empty($rankings)) {
                DB::table('student_rankings')->insert($rankings);
            }
        }

        $this->command->info('✅ Đã tạo xếp hạng cho ' . StudentRanking::count() . ' học sinh');
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
}
