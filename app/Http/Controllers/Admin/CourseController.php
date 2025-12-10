<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /** LIST COURSES (NEO UI) */
   public function index(Request $request)
{
    $query = ClassRoom::with(['subject', 'teacher'])
        ->withCount(['enrollments' => function ($q) {
            $q->where('status', 'active');
        }]);

    // Search theo tên khóa học
    if ($request->search) {
        $query->where('name', 'LIKE', '%' . $request->search . '%');
    }

    // Lọc theo danh mục (subject_id)
    if ($request->category) {
        $query->where('subject_id', $request->category);
    }

    // Lọc theo giáo viên
    if ($request->teacher) {
        $query->where('teacher_id', $request->teacher);
    }

    // Lọc theo trạng thái
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $courses = $query->orderBy('created_at', 'desc')->paginate(12);

    // Danh mục & giáo viên cho bộ lọc
    $allCategories = Subject::orderBy('name')->get();
    $teachers      = User::role('teacher')->get();

    /**
     * MAP trạng thái:
     * - active  => Đang hoạt động
     * - draft   => Chờ duyệt
     * - closed  => Tạm ẩn
     */
    $stats = [
        'total'   => ClassRoom::count(),
        'active'  => ClassRoom::where('status', 'active')->count(),
        'pending' => ClassRoom::where('status', 'draft')->count(),
        'hidden'  => ClassRoom::where('status', 'closed')->count(),
    ];

    return view('admin.courses.index', compact(
        'courses',
        'stats',
        'allCategories',
        'teachers'
    ));
}

    /** CREATE COURSE */
    public function create(Request $request)
    {
        $subjects = Subject::all();
        $teachers = User::role('teacher')->get();

        // lấy sẵn subject_id từ query (?subject_id=...)
        $selectedSubjectId = $request->get('subject_id');

        return view('admin.courses.create', compact('subjects', 'teachers', 'selectedSubjectId'));
    }

    /** STORE COURSE */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'subject_id'   => 'required|exists:subjects,id',
            'teacher_id'   => 'required|exists:users,id',
            'max_students' => 'required|integer|min:1',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'description'  => 'nullable|string',
        ]);

        // ----- TẠO MÃ CODE CHO LỚP HỌC (vì cột code trong DB NOT NULL) -----
        $subject = Subject::find($validated['subject_id']);

        // Ưu tiên dùng subject->code, nếu không có thì dùng subject->name
        $prefix = $subject
            ? Str::upper(Str::slug($subject->code ?? $subject->name, ''))
            : 'COURSE';

        // Ví dụ: TOAN-20251210-103552-ABCD
        $code = $prefix . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        // -------------------------------------------------------------------

        ClassRoom::create([
            'code'         => $code,                              // QUAN TRỌNG: map đúng cột code
            'name'         => $validated['name'],
            'subject_id'   => $validated['subject_id'],
            'teacher_id'   => $validated['teacher_id'],
            'max_students' => $validated['max_students'],
            'start_date'   => $validated['start_date'],
            'end_date'     => $validated['end_date'] ?? null,
            'description'  => $validated['description'] ?? null,
            'status'       => 'active',                           // mặc định active
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Tạo khóa học thành công!');
    }

    /** EDIT COURSE */
    public function edit($id)
    {
        $course   = ClassRoom::findOrFail($id);
        $subjects = Subject::all();
        $teachers = User::role('teacher')->get();

        return view('admin.courses.edit', compact('course', 'subjects', 'teachers'));
    }

    /** UPDATE COURSE */
    public function update(Request $request, $id)
    {
        $course = ClassRoom::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'subject_id'   => 'required|exists:subjects,id',
            'teacher_id'   => 'required|exists:users,id',
            'max_students' => 'required|integer|min:1',
            'status'       => 'required|in:active,closed,draft',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'description'  => 'nullable|string',
        ]);

        $course->update($validated);

        return back()->with('success', 'Cập nhật thành công!');
    }

    /** DELETE COURSE */
    public function destroy($id)
    {
        ClassRoom::findOrFail($id)->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Xóa khóa học thành công!');
    }
}
