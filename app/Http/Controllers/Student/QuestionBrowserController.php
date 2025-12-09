<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuestionBrowserController extends Controller
{
    /**
     * Display question browser with search and filters
     */
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'topic', 'answers']);

        // Search by question text or topic name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question_text', 'LIKE', "%{$search}%")
                  ->orWhereHas('topic', function($tq) use ($search) {
                      $tq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by topic
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Paginate results
        $questions = $query->latest()->paginate(15);

        // Get all subjects for filter
        $subjects = Subject::all();

        // Get topics based on selected subject
        $topics = collect();
        if ($request->filled('subject_id')) {
            $topics = Topic::where('subject_id', $request->subject_id)->get();
        }

        return view('student.questions.browse', compact('questions', 'subjects', 'topics'));
    }

    /**
     * Get topics by subject (AJAX)
     */
    public function getTopicsBySubject(Request $request)
    {
        $topics = Topic::where('subject_id', $request->subject_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($topics);
    }

    /**
     * Show single question details
     */
    public function show($id)
    {
        $question = Question::with(['subject', 'topic', 'answers'])->findOrFail($id);
        
        return view('student.questions.show', compact('question'));
    }
}
