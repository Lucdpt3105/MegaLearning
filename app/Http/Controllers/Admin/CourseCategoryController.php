<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    /**
     * Danh sách danh mục + thống kê
     */
    public function index(Request $request)
{
    $query = CourseCategory::query()
        ->with(['courses'])          // lấy list class_rooms
        ->withCount('courses');      // đếm số khóa học (class_rooms) thuộc subject này

    // Tìm kiếm theo tên
    if ($request->q) {
        $query->where('name', 'LIKE', '%' . $request->q . '%');
    }

    $categories = $query->orderBy('name')->get();

    // Thống kê dùng cho 4 ô bên trên + danh mục phổ biến
    $stats = [
        'total_categories'    => CourseCategory::count(),
        'active_categories'   => CourseCategory::where('status', 'active')->count(),
        'total_courses'       => ClassRoom::count(),
        // hiện m chưa dùng danh mục phụ thực sự, cho = 0
        'total_subcategories' => 0,
        // top 5 danh mục có nhiều khóa học nhất (dùng cho cột "Danh mục phổ biến")
        'popular'             => CourseCategory::withCount('courses')
                                    ->orderByDesc('courses_count')
                                    ->take(5)
                                    ->get(),
        // cái này view không dùng nhưng nếu thích thì giữ lại
        'draft_categories'    => CourseCategory::where('status', 'draft')->count(),
    ];

    return view('admin.course_categories.index', compact('categories', 'stats'));
}

    /**
     * Form tạo mới (nếu dùng trang riêng)
     */
    public function create()
    {
        return view('admin.course_categories.create');
    }

    /**
     * Lưu danh mục (subject mới)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            // dùng enum thật trong DB: draft / active / archived
            'status'      => 'required|in:draft,active,archived',
        ]);

        // Bảng subjects bắt buộc có code, nên tự generate từ name
       // Bảng subjects bắt buộc có code, nên tự generate từ name (và đảm bảo không trùng)
$baseCode = Str::upper(Str::slug($data['name'], '_'));
$code     = $baseCode;
$index    = 1;

while (CourseCategory::where('code', $code)->exists()) {
    $code = $baseCode . '_' . $index;
    $index++;
}

$data['code'] = $code;

CourseCategory::create($data);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', 'Tạo danh mục thành công!');
    }

    public function edit($id)
    {
        $category = CourseCategory::findOrFail($id);
        return view('admin.course_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = CourseCategory::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:draft,active,archived',
        ]);

        $data['code'] = Str::upper(Str::slug($data['name'], '_'));

        $category->update($data);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = CourseCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Đã xóa danh mục!');
    }
}
