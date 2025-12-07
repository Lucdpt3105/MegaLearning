<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;

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
        $stats = [
            'available_exams' => Exam::count(),
            'subjects_count' => Subject::count(),
            'completed_exams' => 0, // TODO: Implement exam results tracking
            'total_score' => 0, // TODO: Implement scoring
        ];

        $recentExams = Exam::latest()->take(5)->get();
        $subjects = Subject::withCount('topics')->take(6)->get();

        return view('welcome', compact('stats', 'recentExams', 'subjects'));
    }
}
