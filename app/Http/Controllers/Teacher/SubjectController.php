<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SubjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Xem danh sách môn học
     * Display a listing of the teacher's subjects.
     */
    public function index()
    {
        $teacher = Auth::user();
        
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->withCount(['classRooms', 'documents', 'exams', 'topics'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.subjects.index', compact('subjects'));
    }

    /**
     * Thêm môn học mới
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('teacher.subjects.create');
    }

    /**
     * Thêm môn học mới
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
        ]);

        $teacher = Auth::user();

        // Tự động generate code nếu không có
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateSubjectCode($validated['name']);
        }

        DB::beginTransaction();
        try {
            // Create subject
            $subject = Subject::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'] ?? null,
                'teacher_id' => $teacher->id,
                'status' => $validated['status'],
            ]);

            // Tự động tạo nhóm chat cho môn học
            if ($request->has('create_chat_room') && $request->create_chat_room) {
                $chatRoom = ChatRoom::create([
                    'room_name' => "Chat - {$subject->name}",
                    'room_type' => 'subject',
                    'subject_id' => $subject->id,
                    'created_by' => $teacher->id,
                    'is_active' => true,
                ]);

                // Add teacher as admin of the chat room
                $chatRoom->members()->attach($teacher->id, [
                    'role' => 'admin',
                    'joined_at' => now()
                ]);
            }

            DB::commit();

            return redirect()
                ->route('teacher.subjects.index')
                ->with('success', 'Môn học đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('view', $subject);

        $subject->load([
            'classRooms' => function($query) {
                $query->withCount(['students' => function($q) {
                    $q->where('class_enrollments.status', 'active');
                }]);
            },
            'documents' => function($query) {
                $query->latest()->with(['uploader', 'approver']);
            },
            'exams' => function($query) {
                $query->latest()->limit(5);
            },
            'topics' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        return view('teacher.subjects.show', compact('subject'));
    }

    /**
     * Cập nhật thông tin môn học
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('update', $subject);

        return view('teacher.subjects.edit', compact('subject'));
    }

    /**
     * Cập nhật thông tin môn học
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('update', $subject);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
        ]);

        try {
            $subject->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('teacher.subjects.index')
                ->with('success', 'Môn học đã được cập nhật thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa môn học
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('delete', $subject);

        try {
            // Check if subject has any data
            $hasClassRooms = $subject->classRooms()->exists();
            $hasExams = $subject->exams()->exists();
            $hasDocuments = $subject->documents()->exists();

            if ($hasClassRooms || $hasExams || $hasDocuments) {
                return redirect()
                    ->back()
                    ->with('error', 'Không thể xóa môn học vì đã có dữ liệu liên quan (lớp học, đề thi, tài liệu). Vui lòng chuyển sang trạng thái "Lưu trữ" thay vì xóa.');
            }

            $subject->delete();

            return redirect()
                ->route('teacher.subjects.index')
                ->with('success', 'Môn học đã được xóa thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Quản lý nhóm chat của môn học
     */
    public function manageChatRoom(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('view', $subject);

        $chatRoom = ChatRoom::where('subject_id', $subject->id)
            ->where('room_type', 'subject')
            ->with(['members', 'messages' => function($query) {
                $query->latest()->limit(50);
            }])
            ->first();

        if (!$chatRoom) {
            return redirect()
                ->back()
                ->with('error', 'Môn học này chưa có nhóm chat. Vui lòng tạo mới.');
        }

        // Get students enrolled in classes of this subject who are not members yet
        $existingMemberIds = $chatRoom->members->pluck('id')->toArray();
        
        // Get all users (students and teachers) who are not members yet
        // Using Spatie Permission: roles are in model_has_roles table
        $availableUsers = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->whereNotIn('users.id', $existingMemberIds)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->whereIn('roles.name', ['student', 'teacher'])
            ->select('users.id', 'users.name', 'users.email', 'roles.name as role')
            ->orderByRaw("FIELD(roles.name, 'teacher', 'student')") // Teachers first
            ->orderBy('users.name')
            ->distinct()
            ->get();

        return view('teacher.subjects.chat-room', compact('subject', 'chatRoom', 'availableUsers'));
    }

    /**
     * Tạo nhóm chat cho môn học
     */
    public function createChatRoom(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('update', $subject);

        try {
            // Check if chat room already exists
            $existingRoom = ChatRoom::where('subject_id', $subject->id)
                ->where('room_type', 'subject')
                ->first();

            if ($existingRoom) {
                return redirect()
                    ->back()
                    ->with('error', 'Môn học này đã có nhóm chat.');
            }

            $teacher = Auth::user();

            $chatRoom = ChatRoom::create([
                'room_name' => "Chat - {$subject->name}",
                'room_type' => 'subject',
                'subject_id' => $subject->id,
                'created_by' => $teacher->id,
                'is_active' => true,
            ]);

            // Add teacher as admin
            $chatRoom->members()->attach($teacher->id, [
                'role' => 'admin',
                'joined_at' => now()
            ]);

            // Auto-add all students enrolled in classes of this subject
            $students = DB::table('class_enrollments')
                ->join('class_rooms', 'class_enrollments.class_room_id', '=', 'class_rooms.id')
                ->where('class_rooms.subject_id', $subject->id)
                ->pluck('class_enrollments.student_id')
                ->unique();

            foreach ($students as $studentId) {
                $chatRoom->members()->attach($studentId, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
            }

            return redirect()
                ->route('teacher.subjects.show', $subject)
                ->with('success', 'Nhóm chat đã được tạo thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Thêm thành viên vào nhóm chat
     */
    public function addChatMember(Subject $subject, Request $request)
    {
        $this->authorize('update', $subject);

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $chatRoom = ChatRoom::where('subject_id', $subject->id)
                ->where('room_type', 'subject')
                ->firstOrFail();

            $addedCount = 0;
            foreach ($request->user_ids as $userId) {
                // Check if already member
                if (!$chatRoom->members()->where('user_id', $userId)->exists()) {
                    $chatRoom->members()->attach($userId, [
                        'role' => 'member',
                        'joined_at' => now()
                    ]);
                    $addedCount++;
                }
            }

            return redirect()
                ->back()
                ->with('success', "Đã thêm {$addedCount} thành viên vào nhóm chat!");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa thành viên khỏi nhóm chat
     */
    public function removeChatMember(Subject $subject, $userId)
    {
        $this->authorize('update', $subject);

        try {
            $chatRoom = ChatRoom::where('subject_id', $subject->id)
                ->where('room_type', 'subject')
                ->firstOrFail();

            // Check if member exists and is not admin
            $member = $chatRoom->members()->where('user_id', $userId)->first();
            
            if (!$member) {
                return redirect()
                    ->back()
                    ->with('error', 'Thành viên không tồn tại trong nhóm chat.');
            }

            if ($member->pivot->role === 'admin') {
                return redirect()
                    ->back()
                    ->with('error', 'Không thể xóa quản trị viên khỏi nhóm chat.');
            }

            $chatRoom->members()->detach($userId);

            return redirect()
                ->back()
                ->with('success', 'Đã xóa thành viên khỏi nhóm chat!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Đóng/Mở phòng chat
     */
    public function toggleChatStatus(Subject $subject)
    {
        $this->authorize('update', $subject);

        try {
            $chatRoom = ChatRoom::where('subject_id', $subject->id)
                ->where('room_type', 'subject')
                ->firstOrFail();

            $chatRoom->is_active = !$chatRoom->is_active;
            $chatRoom->save();

            $status = $chatRoom->is_active ? 'mở' : 'đóng';

            return redirect()
                ->back()
                ->with('success', "Đã {$status} phòng chat thành công!");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Get topics for a subject (API endpoint for auto-generate)
     */
    public function getTopics(Subject $subject)
    {
        // Check ownership
        if ($subject->teacher_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $topics = $subject->topics()
            ->withCount(['questions' => function($query) {
                $query->where('in_question_bank', true);
            }])
            ->get()
            ->map(function($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'questions_count' => $topic->questions_count,
                ];
            });

        return response()->json($topics);
    }

    /**
     * Tự động generate mã môn học duy nhất
     */
    private function generateSubjectCode($name)
    {
        // Lấy chữ cái đầu của mỗi từ
        $words = explode(' ', strtoupper($name));
        $code = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                // Loại bỏ dấu tiếng Việt
                $word = $this->removeVietnameseTones($word);
                $code .= substr($word, 0, 1);
            }
        }
        
        // Nếu code quá ngắn, lấy thêm ký tự
        if (strlen($code) < 3) {
            $cleanName = $this->removeVietnameseTones(strtoupper(str_replace(' ', '', $name)));
            $code = substr($cleanName, 0, 6);
        }
        
        // Kiểm tra trùng và thêm số nếu cần
        $originalCode = $code;
        $counter = 1;
        
        while (Subject::where('code', $code)->exists()) {
            $code = $originalCode . $counter;
            $counter++;
        }
        
        return $code;
    }

    /**
     * Loại bỏ dấu tiếng Việt
     */
    private function removeVietnameseTones($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        
        return $str;
    }
}
