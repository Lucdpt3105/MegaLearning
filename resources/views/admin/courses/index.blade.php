@extends('admin.layout')

@section('title', 'Khóa học')
@section('page-title', 'Quản lý Khóa học')
@section('page-description', 'Xem, lọc và quản lý toàn bộ khóa học trong hệ thống.')

@section('content')
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tổng khóa học</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">
                    {{ $stats['total'] ?? 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center">
                <i class="ri-book-open-line text-lg text-indigo-600"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Đang hoạt động</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-600">
                    {{ $stats['active'] ?? 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center">
                <i class="ri-play-circle-line text-lg text-emerald-600"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Chờ duyệt</p>
                <p class="mt-2 text-2xl font-semibold text-amber-500">
                    {{ $stats['pending'] ?? 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center">
                <i class="ri-time-line text-lg text-amber-500"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tạm ẩn</p>
                <p class="mt-2 text-2xl font-semibold text-rose-500">
                    {{ $stats['hidden'] ?? 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center">
                <i class="ri-eye-off-line text-lg text-rose-500"></i>
            </div>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Danh mục</label>
                <select name="category"
                        class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    @foreach($allCategories ?? [] as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Trạng thái</label>
                <select name="status"
                        class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="active" @selected(request('status') === 'active')>Đang hoạt động</option>
                    <option value="pending" @selected(request('status') === 'pending')>Chờ duyệt</option>
                    <option value="hidden" @selected(request('status') === 'hidden')>Tạm ẩn</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Giáo viên</label>
                <select name="teacher"
                        class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    @foreach($teachers ?? [] as $teacher)
                        <option value="{{ $teacher->id }}" @selected(request('teacher') == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col justify-end gap-2">
                <button type="submit"
                        class="w-full rounded-xl bg-indigo-600 text-white text-sm font-medium px-3 py-2 hover:bg-indigo-700">
                    <i class="ri-filter-3-line mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.courses.index') }}"
                   class="w-full rounded-xl border border-slate-200 text-xs text-slate-600 px-3 py-2 text-center hover:bg-slate-50">
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>

    {{-- Tiêu đề + nút thêm --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h2 class="text-base font-semibold text-slate-900">
            Danh sách Khóa học
        </h2>
        <a href="{{ route('admin.courses.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 text-white text-sm font-medium px-4 py-2 hover:bg-indigo-700">
            <i class="ri-add-line mr-1 text-lg"></i> Thêm khóa học
        </a>
    </div>

    {{-- Grid khóa học --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($courses as $course)
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden flex flex-col shadow-sm">
                {{-- Ảnh cover --}}
                <div class="relative">
                    <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/400x200?text=Course' }}"
                         alt="{{ $course->title }}"
                         class="w-full h-40 object-cover">
                    @if($course->status === 'active')
                        <span class="absolute top-2 right-2 inline-flex items-center rounded-full bg-emerald-500 text-white text-[11px] px-2.5 py-1 font-semibold">
                            Hoạt động
                        </span>
                    @elseif($course->status === 'pending')
                        <span class="absolute top-2 right-2 inline-flex items-center rounded-full bg-amber-400 text-[11px] px-2.5 py-1 font-semibold">
                            Chờ duyệt
                        </span>
                    @else
                        <span class="absolute top-2 right-2 inline-flex items-center rounded-full bg-slate-500 text-white text-[11px] px-2.5 py-1 font-semibold">
                            Tạm ẩn
                        </span>
                    @endif
                </div>

                <div class="flex-1 p-4 flex flex-col">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        @if($course->category)
                            <span class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-semibold px-2 py-1">
                                {{ $course->category->name }}
                            </span>
                        @else
                            <span class="text-[11px] text-slate-400 italic">Chưa có danh mục</span>
                        @endif>

                        <span class="text-[11px] text-amber-500 flex items-center gap-1">
                            <i class="ri-star-fill"></i> {{ number_format($course->rating ?? 0, 1) }}
                        </span>
                    </div>

                    <h3 class="text-sm font-semibold text-slate-900 mb-1 line-clamp-2">
                        {{ $course->title }}
                    </h3>
                    <p class="text-xs text-slate-500 mb-3 line-clamp-3">
                        {{ $course->short_description ?? 'Chưa có mô tả ngắn.' }}
                    </p>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 mb-3">
                        <span><i class="ri-user-line mr-1"></i>{{ $course->students_count ?? 0 }} học viên</span>
                        <span><i class="ri-book-2-line mr-1"></i>{{ $course->lessons_count ?? 0 }} bài học</span>
                    </div>

                    <div class="flex items-center mb-4">
                        <div class="w-7 h-7 rounded-full overflow-hidden mr-2">
                            <img src="{{ $course->teacher->avatar_url ?? asset('images/default-avatar.png') }}"
                                 class="w-full h-full object-cover" alt="">
                        </div>
                        <span class="text-xs text-slate-600">
                            {{ $course->teacher->name ?? 'Chưa gán giáo viên' }}
                        </span>
                    </div>

                    <div class="mt-auto flex items-center justify-between text-sm">
                        <div class="font-semibold text-indigo-600">
                            {{ $course->price_label ?? 'Miễn phí' }}
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.courses.show', $course) }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 hover:bg-slate-50">
                                <i class="ri-eye-line text-slate-500 text-lg"></i>
                            </a>
                            <a href="{{ route('admin.courses.edit', $course) }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 hover:bg-slate-50">
                                <i class="ri-edit-line text-amber-500 text-lg"></i>
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course) }}"
                                  method="POST"
                                  onsubmit="return confirm('Xóa khóa học này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 hover:bg-rose-50">
                                    <i class="ri-delete-bin-line text-rose-500 text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500 col-span-full">
                Chưa có khóa học nào. <a href="{{ route('admin.courses.create') }}" class="text-indigo-600 font-medium">Tạo khóa học đầu tiên?</a>
            </p>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($courses instanceof \Illuminate\Pagination\LengthAwarePaginator && $courses->hasPages())
        <div class="mt-6">
            {{ $courses->withQueryString()->links() }}
        </div>
    @endif
@endsection
