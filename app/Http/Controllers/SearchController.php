<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Document;
use App\Models\ForumQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Handle global search request
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query', '');
            
            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'results' => [
                        'exams' => [],
                        'subjects' => [],
                        'topics' => [],
                        'documents' => [],
                        'forum_questions' => [],
                    ],
                    'total' => 0,
                ]);
            }

            $user = Auth::user();
            $results = [];

            // Search Exams (Quizzes)
            $exams = Exam::with(['subject', 'classRoom'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->where('status', 'published')
                ->limit(5)
                ->get()
                ->map(function($exam) use ($user) {
                    return [
                        'id' => $exam->id,
                        'title' => $exam->title,
                        'description' => $exam->description,
                        'subject' => $exam->subject->name ?? 'N/A',
                        'type' => 'exam',
                        'url' => $this->getExamUrl($exam, $user),
                    ];
                });

            // Search Subjects (Courses)
            $subjects = Subject::with('teacher')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('code', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->where('status', 'active')
                ->limit(5)
                ->get()
                ->map(function($subject) use ($user) {
                    return [
                        'id' => $subject->id,
                        'title' => $subject->name,
                        'code' => $subject->code,
                        'description' => $subject->description,
                        'teacher' => $subject->teacher->name ?? 'N/A',
                        'type' => 'subject',
                        'url' => $this->getSubjectUrl($subject, $user),
                    ];
                });

            // Search Topics
            $topics = Topic::with('subject')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function($topic) use ($user) {
                    return [
                        'id' => $topic->id,
                        'title' => $topic->name,
                        'description' => $topic->description,
                        'subject' => $topic->subject->name ?? 'N/A',
                        'type' => 'topic',
                        'url' => $this->getTopicUrl($topic, $user),
                    ];
                });

            // Search Documents
            $documents = Document::with(['subject', 'uploader'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('file_name', 'like', "%{$query}%");
                })
                ->where('approval_status', 'approved')
                ->limit(5)
                ->get()
                ->map(function($document) use ($user) {
                    return [
                        'id' => $document->id,
                        'title' => $document->title,
                        'description' => $document->description,
                        'subject' => $document->subject->name ?? 'N/A',
                        'file_type' => $document->file_type,
                        'type' => 'document',
                        'url' => $this->getDocumentUrl($document, $user),
                    ];
                });

            // Search Forum Questions
            $forumQuestions = ForumQuestion::with('user')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function($question) {
                    return [
                        'id' => $question->forum_question_id,
                        'title' => $question->title,
                        'content' => substr(strip_tags($question->content), 0, 100) . '...',
                        'author' => $question->user->name ?? 'Unknown',
                        'type' => 'forum',
                        'url' => route('forum.show', $question->forum_question_id),
                    ];
                });

            $results = [
                'exams' => $exams,
                'subjects' => $subjects,
                'topics' => $topics,
                'documents' => $documents,
                'forum_questions' => $forumQuestions,
            ];

            $total = $exams->count() + $subjects->count() + $topics->count() + 
                     $documents->count() + $forumQuestions->count();

            return response()->json([
                'success' => true,
                'results' => $results,
                'total' => $total,
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching. Please try again.',
                'results' => [
                    'exams' => [],
                    'subjects' => [],
                    'topics' => [],
                    'documents' => [],
                    'forum_questions' => [],
                ],
                'total' => 0,
            ], 500);
        }
    }

    /**
     * Get URL for exam based on user role
     */
    private function getExamUrl($exam, $user)
    {
        if ($user->hasRole('student')) {
            return route('student.exams.show', $exam->id);
        } elseif ($user->hasRole('teacher')) {
            return route('teacher.exams.show', $exam->id);
        } elseif ($user->hasRole('admin')) {
            return route('admin.exams.edit', $exam->id);
        }
        return '#';
    }

    /**
     * Get URL for subject based on user role
     */
    private function getSubjectUrl($subject, $user)
    {
        if ($user->hasRole('teacher')) {
            return route('teacher.subjects.show', $subject->id);
        } elseif ($user->hasRole('admin')) {
            return route('admin.subjects.show', $subject->id);
        }
        // For students, subjects might be accessible through courses
        return route('student.courses.index');
    }

    /**
     * Get URL for topic based on user role
     */
    private function getTopicUrl($topic, $user)
    {
        // Topics are usually accessed through subjects
        if ($user->hasRole('teacher')) {
            return route('teacher.subjects.show', $topic->subject_id);
        } elseif ($user->hasRole('admin')) {
            return route('admin.subjects.show', $topic->subject_id);
        }
        return route('student.courses.index');
    }

    /**
     * Get URL for document based on user role
     */
    private function getDocumentUrl($document, $user)
    {
        if ($user->hasRole('teacher')) {
            return route('teacher.documents.show', $document->id);
        }
        // For students and others, might need a general document view
        return '#';
    }
}
