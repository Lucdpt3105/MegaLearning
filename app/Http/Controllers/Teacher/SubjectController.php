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
     * UC-GV-014: Xem danh sách môn học
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
     * UC-GV-011: Thêm môn học mới
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('teacher.subjects.create');
    }

    /**
     * UC-GV-011: Thêm môn học mới
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
        ]);

        $teacher = Auth::user();

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

            // UC-GV-015: Tự động tạo nhóm chat cho môn học
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
            'classRooms',
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
     * UC-GV-012: Cập nhật thông tin môn học
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        // Check if teacher owns this subject
        $this->authorize('update', $subject);

        return view('teacher.subjects.edit', compact('subject'));
    }

    /**
     * UC-GV-012: Cập nhật thông tin môn học
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
     * UC-GV-013: Xóa môn học
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
     * UC-GV-015: Quản lý nhóm chat của môn học
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
     * UC-GV-015: Tạo nhóm chat cho môn học
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
     * UC-GV-015: Thêm thành viên vào nhóm chat
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
     * UC-GV-015: Xóa thành viên khỏi nhóm chat
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
     * UC-GV-015: Đóng/Mở phòng chat
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
}

