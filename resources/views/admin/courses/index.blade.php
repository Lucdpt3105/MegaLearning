@extends('admin.layout')

@section('title', 'Quản lý khóa học')
@section('page-title', 'Quản lý khóa học')
@section('page-description', 'Danh sách toàn bộ khóa học trong hệ thống.')

@section('content')

{{-- Toolbar --}}
<div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">

    <div class="flex items-center gap-3">
        <form method="GET" class="flex items-center gap-3">

            {{-- Filter by subject --}}
            <select name="subject"
                    class="px-4 py-2 rounded-xl border border-slate-300 focus:ring-indigo-500 text-sm">
                <option value="">Tất cả môn học</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by teacher --}}
            <select name="teacher"
                    class="px-4 py-2 rounded-xl border border-slate-300 focus:ring-indigo-500 text-sm">
                <option value="">Tất cả giáo viên</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by status --}}
            <select name="status"
                    class="px-4 py-2 rounded-xl border border-slate-300 focus:ring-indigo-500 text-sm">
                <option value="">Trạng thái</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Đang mở</option>
                <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Đã đóng</option>
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm shadow">
                Lọc
            </button>
        </form>
    </div>

    <a href="{{ route('admin.courses.create') }}"
       class="px-5 py-2 bg-purple-600 text-white rounded-xl shadow hover:bg-purple-700 text-sm flex items-center gap-2">
        <i data-feather="plus-circle" class="w-4 h-4"></i>
        Thêm khóa học
    </a>
</div>


{{-- Courses Table --}}
<div class="bg-white border border-slate-200 shadow rounded-2xl overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-700">
            <tr>
                <th class="px-4 py-3 text-left">Khóa học</th>
                <th class="px-4 py-3 text-left">Môn học</th>
                <th class="px-4 py-3 text-left">Giáo viên</th>
                <th class="px-4 py-3 text-center">Học viên</th>
                <th class="px-4 py-3 text-center">Ngày bắt đầu</th>
                <th class="px-4 py-3 text-center">Trạng thái</th>
                <th class="px-4 py-3 text-center">Thao tác</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($courses as $course)
            <tr class="border-t border-slate-200 hover:bg-slate-50">

                {{-- Course name --}}
                <td class="px-4 py-3 font-medium text-slate-900">
                    {{ $course->name }}
                </td>

                {{-- Subject --}}
                <td class="px-4 py-3 text-slate-700">
                    {{ $course->subject->name }}
                </td>

                {{-- Teacher --}}
                <td class="px-4 py-3 flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ $course->teacher->name }}&size=32"
                         class="h-7 w-7 rounded-full">
                    <span>{{ $course->teacher->name }}</span>
                </td>

                {{-- Students count --}}
                <td class="px-4 py-3 text-center font-semibold text-indigo-700">
                    {{ $course->active_students_count }} / {{ $course->max_students }}
                </td>

                {{-- Start date --}}
                <td class="px-4 py-3 text-center text-slate-600">
                    {{ $course->start_date->format('d/m/Y') }}
                </td>

                {{-- Status --}}
                <td class="px-4 py-3 text-center">
                    @if ($course->status == 'active')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                            Đang mở
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                            Đã đóng
                        </span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 text-center flex items-center justify-center gap-2">

                    <a href="{{ route('admin.courses.show', $course->id) }}"
                        class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200">
                        <i data-feather="eye" class="w-4 h-4 text-slate-700"></i>
                    </a>

                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                        class="p-2 rounded-lg bg-indigo-100 hover:bg-indigo-200">
                        <i data-feather="edit" class="w-4 h-4 text-indigo-700"></i>
                    </a>

                    <form action="{{ route('admin.courses.destroy', $course->id) }}"
                          method="POST"
                          onsubmit="return confirm('Xác nhận xóa khóa học?')">
                        @csrf
                        @method('DELETE')
                        <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200">
                            <i data-feather="trash" class="w-4 h-4 text-red-700"></i>
                        </button>
                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $courses->links() }}
</div>

@endsection
