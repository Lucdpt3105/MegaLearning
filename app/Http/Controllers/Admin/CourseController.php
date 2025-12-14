<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /** LIST COURSES (NEO UI) */
    public function index(Request $request)
    {
        $query = ClassRoom::with(['subject', 'teacher'])
            ->withCount(['enrollments' => function($q) {
                $q->where('status', 'active');
            }]);

        // Search
        if ($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('admin.courses.index', compact('courses'));
    }

    /** CREATE COURSE */
    public function create()
    {
        $subjects = Subject::all();
        $teachers = User::role('teacher')->get();

        return view('admin.courses.create', compact('subjects', 'teachers'));
    }

    /** STORE COURSE */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:class_rooms,code',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        // Auto-generate unique code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $validated['name']), 0, 6)) . '-' . time();
        }

        ClassRoom::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'max_students' => $validated['max_students'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'description' => $validated['description'],
            'status' => 'active',
        ]);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Tạo lớp học thành công!');
    }

    /** EDIT COURSE */
    public function edit($id)
    {
        $course = ClassRoom::findOrFail($id);
        $subjects = Subject::all();
        $teachers = User::role('teacher')->get();

        return view('admin.courses.edit', compact('course', 'subjects', 'teachers'));
    }

    /** UPDATE COURSE */
    public function update(Request $request, $id)
    {
        $course = ClassRoom::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:class_rooms,code,' . $id,
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:active,closed,draft',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return back()->with('success', 'Cập nhật thành công!');
    }

    /** DELETE COURSE */
    public function destroy($id)
    {
        ClassRoom::findOrFail($id)->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Xóa lớp học thành công!');
    }
}
