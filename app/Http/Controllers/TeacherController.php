<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Exam;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Teacher Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'subjects_count' => Subject::count(),
            'topics_count' => Topic::count(),
            'questions_count' => Question::count(),
            'exams_count' => Exam::count(),
        ];

        return view('teacher.dashboard', compact('stats'));
    }

    /**
     * Manage Subjects
     */
    public function subjects()
    {
        $subjects = Subject::withCount('topics')->paginate(10);
        return view('teacher.subjects', compact('subjects'));
    }

    /**
     * Manage Topics
     */
    public function topics()
    {
        $topics = Topic::with('subject')->paginate(10);
        return view('teacher.topics', compact('topics'));
    }

    /**
     * Manage Questions
     */
    public function questions()
    {
        $questions = Question::with('topic')->paginate(10);
        return view('teacher.questions', compact('questions'));
    }

    /**
     * Manage Exams
     */
    public function exams()
    {
        $exams = Exam::withCount('questions')->paginate(10);
        return view('teacher.exams', compact('exams'));
    }
}
