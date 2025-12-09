<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Document;
use App\Models\ForumQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Global search across courses, documents, forums
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'all'); // all, courses, documents, forums

        if (empty($query)) {
            return redirect()->back();
        }

        $results = [
            'query' => $query,
            'type' => $type,
            'courses' => collect(),
            'documents' => collect(),
            'forums' => collect(),
            'subjects' => collect(),
        ];

        $user = Auth::user();

        // Search courses (for students)
        if (($type === 'all' || $type === 'courses') && $user->hasRole('student')) {
            $enrolledClassIds = $user->enrolledClasses()->pluck('class_rooms.id');
            
            $results['courses'] = ClassRoom::with(['subject', 'teacher'])
                ->withCount([
                    'students as active_students_count' => function($q) {
                        $q->where('class_enrollments.status', 'active');
                    }
                ])
                ->where('status', 'active')
                ->whereNotIn('id', $enrolledClassIds)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhereHas('subject', function($subQ) use ($query) {
                          $subQ->where('name', 'LIKE', "%{$query}%")
                               ->orWhere('description', 'LIKE', "%{$query}%");
                      })
                      ->orWhereHas('teacher', function($teacherQ) use ($query) {
                          $teacherQ->where('name', 'LIKE', "%{$query}%");
                      });
                })
                ->whereRaw('(SELECT COUNT(*) FROM class_enrollments 
                           WHERE class_room_id = class_rooms.id 
                           AND status = "active") < max_students')
                ->limit(10)
                ->get();
        }

        // Search documents (for all authenticated users)
        if ($type === 'all' || $type === 'documents') {
            $documentsQuery = Document::with(['subject', 'uploadedBy'])
                ->where('approval_status', 'approved')
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('original_name', 'LIKE', "%{$query}%")
                      ->orWhereHas('subject', function($subQ) use ($query) {
                          $subQ->where('name', 'LIKE', "%{$query}%");
                      });
                });

            // If student, filter by enrolled courses
            if ($user->hasRole('student')) {
                $enrolledSubjectIds = $user->enrolledClasses()
                    ->pluck('subject_id')
                    ->unique();
                $documentsQuery->whereIn('subject_id', $enrolledSubjectIds);
            }

            $results['documents'] = $documentsQuery->limit(10)->get();
        }

        // Search forum questions (for all authenticated users)
        if ($type === 'all' || $type === 'forums') {
            $results['forums'] = ForumQuestion::with(['user', 'votes'])
                ->withCount('answers')
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%")
                      ->orWhere('tags', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Search subjects (for context)
        if ($type === 'all' || $type === 'subjects') {
            $results['subjects'] = Subject::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();
        }

        $results['total'] = $results['courses']->count() 
                          + $results['documents']->count() 
                          + $results['forums']->count()
                          + $results['subjects']->count();

        return view('search.results', $results);
    }

    /**
     * AJAX autocomplete search suggestions
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $user = Auth::user();
        $suggestions = [];

        // Quick course suggestions
        if ($user->hasRole('student')) {
            $enrolledClassIds = $user->enrolledClasses()->pluck('class_rooms.id');
            
            $courses = ClassRoom::with('subject')
                ->where('status', 'active')
                ->whereNotIn('id', $enrolledClassIds)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhereHas('subject', function($subQ) use ($query) {
                          $subQ->where('name', 'LIKE', "%{$query}%");
                      });
                })
                ->limit(5)
                ->get()
                ->map(function($course) {
                    return [
                        'type' => 'course',
                        'title' => $course->name,
                        'subtitle' => $course->subject->name ?? '',
                        'url' => route('student.courses.browse', ['search' => $course->name]),
                        'icon' => '📚'
                    ];
                });

            $suggestions = array_merge($suggestions, $courses->toArray());
        }

        // Forum suggestions
        $forums = ForumQuestion::where('title', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function($forum) {
                return [
                    'type' => 'forum',
                    'title' => $forum->title,
                    'subtitle' => 'Diễn đàn thảo luận',
                    'url' => route('forum.show', $forum->forum_question_id),
                    'icon' => '💬'
                ];
            });

        $suggestions = array_merge($suggestions, $forums->toArray());

        // Subject suggestions
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function($subject) use ($user) {
                return [
                    'type' => 'subject',
                    'title' => $subject->name,
                    'subtitle' => 'Môn học',
                    'url' => $user->hasRole('student') 
                        ? route('student.courses.browse', ['subject_id' => $subject->id])
                        : '#',
                    'icon' => '🎓'
                ];
            });

        $suggestions = array_merge($suggestions, $subjects->toArray());

        return response()->json(array_slice($suggestions, 0, 8));
    }
}
