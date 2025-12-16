<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSearchController extends Controller
{
    /**
     * Tìm kiếm toàn cục trong admin panel
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Vui lòng nhập ít nhất 2 ký tự'
            ]);
        }

        $results = [
            'users' => $this->searchUsers($query),
            'classes' => $this->searchClasses($query),
            'subjects' => $this->searchSubjects($query),
            'exams' => $this->searchExams($query),
        ];

        // Flatten và limit tổng số kết quả
        $allResults = collect($results)->flatten(1)->take(10);

        return response()->json([
            'results' => $allResults,
            'total' => $allResults->count()
        ]);
    }

    /**
     * Tìm kiếm người dùng
     */
    private function searchUsers($query)
    {
        return User::where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%");
        })
        ->select('id', 'name', 'email', 'role')
        ->limit(5)
        ->get()
        ->map(function($user) {
            return [
                'type' => 'user',
                'title' => $user->name,
                'subtitle' => $user->email,
                'badge' => $this->getRoleBadge($user->role),
                'url' => $this->getUserUrl($user),
                'icon' => 'user'
            ];
        });
    }

    /**
     * Tìm kiếm lớp học
     */
    private function searchClasses($query)
    {
        return ClassRoom::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->with('teacher:id,name')
            ->limit(5)
            ->get()
            ->map(function($class) {
                return [
                    'type' => 'class',
                    'title' => $class->name,
                    'subtitle' => "Mã: {$class->code} - GV: " . ($class->teacher->name ?? 'N/A'),
                    'badge' => 'Lớp học',
                    'url' => route('admin.courses.edit', $class->id),
                    'icon' => 'book-open'
                ];
            });
    }

    /**
     * Tìm kiếm môn học
     */
    private function searchSubjects($query)
    {
        return Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($subject) {
                return [
                    'type' => 'subject',
                    'title' => $subject->name,
                    'subtitle' => \Str::limit($subject->description ?? 'Môn học', 50),
                    'badge' => 'Môn học',
                    'url' => route('admin.courses.index') . '?search=' . urlencode($subject->name),
                    'icon' => 'book'
                ];
            });
    }

    /**
     * Tìm kiếm bài kiểm tra
     */
    private function searchExams($query)
    {
        return Exam::where('title', 'LIKE', "%{$query}%")
            ->with('classRoom:id,name')
            ->limit(5)
            ->get()
            ->map(function($exam) {
                return [
                    'type' => 'exam',
                    'title' => $exam->title,
                    'subtitle' => "Lớp: " . ($exam->classRoom->name ?? 'N/A'),
                    'badge' => 'Bài kiểm tra',
                    'url' => route('admin.courses.index') . '?search=' . urlencode($exam->title),
                    'icon' => 'file-text'
                ];
            });
    }

    /**
     * Get role badge text
     */
    private function getRoleBadge($role)
    {
        return match($role) {
            'admin' => 'Quản trị viên',
            'teacher' => 'Giáo viên',
            'student' => 'Học sinh',
            default => ucfirst($role)
        };
    }

    /**
     * Get user URL based on role
     */
    private function getUserUrl($user)
    {
        return match($user->role) {
            'admin' => route('admin.user.edit', $user->id),
            'teacher' => route('admin.user.edit', $user->id),
            'student' => route('admin.user.edit', $user->id),
            default => route('admin.user.edit', $user->id)
        };
    }

    /**
     * Trang kết quả tìm kiếm đầy đủ
     */
    public function fullSearch(Request $request)
    {
        $query = $request->input('q', '');
        
        $users = User::where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%");
        })->paginate(20, ['*'], 'users_page');

        $classes = ClassRoom::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->with('teacher:id,name')
            ->paginate(20, ['*'], 'classes_page');

        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(20, ['*'], 'subjects_page');

        $exams = Exam::where('title', 'LIKE', "%{$query}%")
            ->with('classRoom:id,name')
            ->paginate(20, ['*'], 'exams_page');

        return view('admin.search.results', compact('query', 'users', 'classes', 'subjects', 'exams'));
    }
}
