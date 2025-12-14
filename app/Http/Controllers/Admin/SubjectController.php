<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index()
    {
        $subjects = Subject::withCount(['topics', 'exams'])
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name',
            'code' => 'nullable|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        // Auto-generate code if not provided
        $code = $request->code ?? strtoupper(substr(preg_replace('/[^A-Z]/i', '', $request->name), 0, 6));
        
        Subject::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Tạo môn học thành công!');
    }

    /**
     * Display the specified subject
     */
    public function show($id)
    {
        $subject = Subject::with(['topics', 'exams'])
            ->withCount(['topics', 'exams'])
            ->findOrFail($id);

        return view('admin.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name,' . $id,
            'code' => 'nullable|string|max:20|unique:subjects,code,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Cập nhật môn học thành công!');
    }

    /**
     * Remove the specified subject
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        // Check if subject has related data
        if ($subject->topics()->count() > 0 || $subject->classRooms()->count() > 0) {
            return redirect()->route('admin.subjects.index')
                ->with('error', 'Không thể xóa môn học đang có chủ đề hoặc lớp học!');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Xóa môn học thành công!');
    }
}
