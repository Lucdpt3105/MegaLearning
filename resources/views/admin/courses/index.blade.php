@extends('admin.layout')

@section('title', 'Quản lý lớp học')
@section('page-title', 'Quản lý lớp học')
@section('page-description', 'Danh sách tất cả các lớp học đang hoạt động trong hệ thống.')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <form class="flex gap-2">
        <input type="text" name="search"
               placeholder="Tìm kiếm khóa học..."
               value="{{ request('search') }}"
               class="px-4 py-2 rounded-xl border border-slate-300 w-64">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-300">
            <option value="">Tất cả trạng thái</option>
            <option value="active" @selected(request('status')=='active')>Đang hoạt động</option>
            <option value="closed" @selected(request('status')=='closed')>Đã đóng</option>
            <option value="draft" @selected(request('status')=='draft')>Bản nháp</option>
        </select>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl">Lọc</button>
    </form>

    <a href="{{ route('admin.courses.create') }}"
       class="px-4 py-2 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700">
        + Tạo khóa học
    </a>
</div>

{{-- LIST COURSES USING NEO CARD STYLE --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    @foreach ($courses as $course)
    <div class="bg-white shadow rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition">

        {{-- Header --}}
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">{{ $course->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $course->code }}</p>
            </div>

            <span class="px-3 py-1 rounded-full text-xs 
            @if($course->status=='active') bg-green-100 text-green-700
            @elseif($course->status=='closed') bg-slate-200 text-slate-700
            @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst($course->status) }}
            </span>
        </div>

        @if($course->subject)
        <p class="text-sm text-slate-500 mt-1">{{ $course->subject->name }}</p>
        @else
        <p class="text-sm text-slate-400 mt-1 italic">Chưa có môn học</p>
        @endif

        {{-- Info --}}
        <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-600">Giáo viên:</span>
                <span class="font-medium">{{ $course->teacher ? $course->teacher->name : 'Chưa có' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-600">Học viên:</span>
                <span class="font-medium">{{ $course->enrollments_count ?? 0 }} / {{ $course->max_students ?? 0 }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-600">Ngày bắt đầu:</span>
                <span class="font-medium">{{ $course->start_date ?? 'Chưa xác định' }}</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-5 flex justify-between">
            <a href="{{ route('admin.courses.edit', $course->id) }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                Chỉnh sửa
            </a>

            <form action="{{ route('admin.courses.destroy', $course->id) }}"
                  method="POST" onsubmit="return confirm('Xóa khóa học?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                    Xóa
                </button>
            </form>
        </div>

    </div>
    @endforeach

</div>

<div class="mt-6">
    {{ $courses->links() }}
</div>

@endsection
