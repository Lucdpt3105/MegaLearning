<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\User;
use App\Models\ClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentController extends Controller
{
    use AuthorizesRequests;

    /**
     * UC-GV-050: Quản lý học sinh - Hiển thị danh sách lớp học
     */
    public function index()
    {
        $teacher = Auth::user();
        
        $classRooms = ClassRoom::where('teacher_id', $teacher->id)
            ->withCount(['students as students_count' => function($query) {
                $query->where('class_enrollments.status', 'active');
            }])
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.students.index', compact('classRooms'));
    }

    /**
     * UC-GV-054: Xem thông tin học sinh trong lớp
     */
    public function show(ClassRoom $classRoom)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập lớp học này.');
        }

        $classRoom->load(['subject', 'students' => function($query) {
            $query->where('class_enrollments.status', 'active')->orderBy('name');
        }]);

        // Get available students to add (all students not in this class or dropped)
        $existingStudentIds = DB::table('class_enrollments')
            ->where('class_room_id', $classRoom->id)
            ->where('status', 'active')
            ->pluck('student_id')
            ->toArray();
        
        $availableStudents = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->whereNotIn('users.id', $existingStudentIds)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('roles.name', 'student')
            ->select('users.id', 'users.name', 'users.email')
            ->orderBy('users.name')
            ->get();

        return view('teacher.students.show', compact('classRoom', 'availableStudents'));
    }

    /**
     * UC-GV-051: Thêm học sinh vào lớp
     */
    public function addStudents(Request $request, ClassRoom $classRoom)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thêm học sinh vào lớp này.');
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $addedCount = 0;
            foreach ($request->student_ids as $studentId) {
                // Check if already enrolled with active status
                $existingEnrollment = ClassEnrollment::where('class_room_id', $classRoom->id)
                    ->where('student_id', $studentId)
                    ->first();

                if ($existingEnrollment && $existingEnrollment->status === 'active') {
                    // Already active, skip
                    continue;
                }

                // Check max students limit (only count active students)
                $activeStudentsCount = ClassEnrollment::where('class_room_id', $classRoom->id)
                    ->where('status', 'active')
                    ->count();

                if ($classRoom->max_students && $activeStudentsCount >= $classRoom->max_students) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with('error', 'Lớp học đã đầy (tối đa ' . $classRoom->max_students . ' học sinh).');
                }

                if ($existingEnrollment) {
                    // Re-activate dropped student
                    $existingEnrollment->update([
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'dropped_at' => null,
                    ]);
                } else {
                    // Create new enrollment
                    ClassEnrollment::create([
                        'class_room_id' => $classRoom->id,
                        'student_id' => $studentId,
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);
                }
                $addedCount++;
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "Đã thêm {$addedCount} học sinh vào lớp học!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * UC-GV-052: Xóa học sinh khỏi lớp
     */
    public function removeStudent(ClassRoom $classRoom, $studentId)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa học sinh khỏi lớp này.');
        }

        try {
            $enrollment = ClassEnrollment::where('class_room_id', $classRoom->id)
                ->where('student_id', $studentId)
                ->first();

            if (!$enrollment) {
                return redirect()
                    ->back()
                    ->with('error', 'Học sinh không tồn tại trong lớp học.');
            }

            // Update status instead of deleting
            $enrollment->update([
                'status' => 'dropped',
                'dropped_at' => now()
            ]);

            return redirect()
                ->back()
                ->with('success', 'Đã xóa học sinh khỏi lớp học!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * UC-GV-053: Cập nhật thông tin học sinh trong lớp (ghi chú)
     */
    public function updateNotes(Request $request, ClassRoom $classRoom, $studentId)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật thông tin học sinh trong lớp này.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $enrollment = ClassEnrollment::where('class_room_id', $classRoom->id)
                ->where('student_id', $studentId)
                ->first();

            if (!$enrollment) {
                return redirect()
                    ->back()
                    ->with('error', 'Học sinh không tồn tại trong lớp học.');
            }

            $enrollment->update([
                'notes' => $request->notes
            ]);

            return redirect()
                ->back()
                ->with('success', 'Đã cập nhật ghi chú cho học sinh!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật thông tin đầy đủ của enrollment (status và notes)
     */
    public function updateEnrollment(Request $request, ClassRoom $classRoom, $studentId)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật thông tin học sinh trong lớp này.');
        }

        $request->validate([
            'status' => 'required|in:active,dropped',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $enrollment = ClassEnrollment::where('class_room_id', $classRoom->id)
                ->where('student_id', $studentId)
                ->first();

            if (!$enrollment) {
                return redirect()
                    ->back()
                    ->with('error', 'Học sinh không tồn tại trong lớp học.');
            }

            $updateData = [
                'status' => $request->status,
                'notes' => $request->notes
            ];

            // If status changed to dropped, set dropped_at
            if ($request->status === 'dropped' && $enrollment->status === 'active') {
                $updateData['dropped_at'] = now();
            }

            $enrollment->update($updateData);

            return redirect()
                ->back()
                ->with('success', 'Đã cập nhật thông tin học sinh!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật thông tin cá nhân của học sinh
     */
    public function updateStudentInfo(Request $request, ClassRoom $classRoom, $studentId)
    {
        // Check if teacher owns this class
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật thông tin học sinh.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Họ và tên không được để trống',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại phải có 10-11 chữ số',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hôm nay',
            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB',
        ]);

        try {
            $student = User::findOrFail($studentId);

            // Check if student is in this class
            $enrollment = ClassEnrollment::where('class_room_id', $classRoom->id)
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->first();

            if (!$enrollment) {
                return redirect()
                    ->back()
                    ->with('error', 'Học sinh không tồn tại trong lớp học này.');
            }

            $updateData = [
                'name' => $request->name,
                'student_id' => $request->student_id,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($student->avatar && \Storage::exists('public/' . $student->avatar)) {
                    \Storage::delete('public/' . $student->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            $student->update($updateData);

            return redirect()
                ->back()
                ->with('success', 'Đã cập nhật thông tin học sinh thành công!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
