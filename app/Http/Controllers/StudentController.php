<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\ExamSubmission;
use App\Models\ClassEnrollment;
use App\Models\VideoCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Student Dashboard (Welcome page)
     */
    public function welcome()
    {
        return $this->dashboard();
    }

    /**
     * Student Dashboard
     */
    public function dashboard()
    {
        $student = Auth::user();
        
        // Get enrolled classes
        $enrolledClasses = $student->enrolledClasses()
            ->where('class_enrollments.status', 'active')
            ->with(['subject'])
            ->get();
        
        $enrolledClassIds = $enrolledClasses->pluck('id');
        
        // Get submissions statistics
        $submissions = ExamSubmission::where('student_id', $student->id)
            ->whereIn('grading_status', ['graded', 'auto_graded'])
            ->get();
        
        // Calculate stats
        $stats = [
            'total_courses' => $enrolledClasses->count(),
            'completed_exams' => $submissions->count(),
            'average_score' => $submissions->avg('score') ?? 0,
            'total_subjects' => $enrolledClasses->unique('subject_id')->count(),
        ];
        
        // Get available exams (published, in enrolled classes, not yet taken or can retake)
        $availableExams = Exam::whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'published')
            ->with(['subject', 'classRoom'])
            ->get()
            ->filter(function($exam) use ($student) {
                $submissionCount = $exam->submissions()->where('student_id', $student->id)->count();
                return $exam->allow_retake || $submissionCount == 0;
            });
        
        // Get upcoming video calls (scheduled for future)
        $upcomingVideoCalls = VideoCall::whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', Carbon::now())
            ->with(['classRoom', 'host'])
            ->get();
        
        // Combine exams and video calls into events
        $upcomingEvents = collect();
        
        // Add exams as events
        foreach ($availableExams as $exam) {
            $upcomingEvents->push([
                'type' => 'exam',
                'id' => $exam->id,
                'title' => $exam->title,
                'subject' => $exam->subject->name ?? 'N/A',
                'class' => $exam->classRoom->name ?? 'N/A',
                'datetime' => $exam->start_time ?? Carbon::now(),
                'duration' => $exam->duration,
                'points' => $exam->total_points,
                'url' => route('student.exams.show', $exam->id),
            ]);
        }
        
        // Add video calls as events
        foreach ($upcomingVideoCalls as $videoCall) {
            $upcomingEvents->push([
                'type' => 'video_call',
                'id' => $videoCall->id,
                'title' => $videoCall->title,
                'subject' => $videoCall->classRoom->subject->name ?? 'N/A',
                'class' => $videoCall->classRoom->name ?? 'N/A',
                'datetime' => $videoCall->scheduled_at,
                'duration' => $videoCall->duration,
                'host' => $videoCall->host->name ?? 'N/A',
                'url' => route('student.video-calls.show', $videoCall->id),
            ]);
        }
        
        // Sort by datetime and take top 5
        $upcomingEvents = $upcomingEvents->sortBy('datetime')->take(5);
        
        // Get recent submissions
        $recentSubmissions = ExamSubmission::where('student_id', $student->id)
            ->with(['exam.subject', 'exam.classRoom'])
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
        
        // Get performance by subject
        $performanceBySubject = $submissions->groupBy(function($submission) {
            return $submission->exam->subject->name ?? 'Unknown';
        })->map(function($subjectSubmissions) {
            return [
                'count' => $subjectSubmissions->count(),
                'average' => $subjectSubmissions->avg('score'),
                'highest' => $subjectSubmissions->max('score'),
            ];
        });
        
        return view('student.dashboard', compact(
            'stats',
            'enrolledClasses',
            'upcomingEvents',
            'recentSubmissions',
            'performanceBySubject'
        ));
    }
}
