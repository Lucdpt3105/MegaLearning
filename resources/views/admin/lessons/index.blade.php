@extends('admin.layout')

@section('title', 'Bài học')
@section('page-title', 'Quản lý bài học')
@section('page-description', 'Quản lý các bài giảng, video, nội dung chi tiết trong từng khóa học.')

@section('content')
    {{-- Filter + action --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Danh sách bài học</h2>
            <p class="text-xs text-slate-500 mt-1">
                Lọc theo khóa học, chương, trạng thái để quản lý nội dung chi tiết.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.lessons.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="course_id"
                        class="text-xs rounded-xl border border-slate-200 px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả khóa học</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>

                <select name="status"
                        class="text-xs rounded-xl border border-slate-200 px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="draft" @selected(request('status') === 'draft')>Nháp</option>
                    <option value="published" @selected(request('status') === 'published')>Đã xuất bản</option>
                </select>

                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Tìm theo tiêu đề bài học..."
                           class="w-44 md:w-60 pl-8 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <i class="ri-search-line text-slate-400 text-sm absolute left-2.5 top-2.5"></i>
                </div>

                <button type="submit"
                        class="inline-flex items-center justify-center px-3 py-2 rounded-xl text-xs font-medium bg-slate-900 text-white hover:bg-slate-800">
                    Lọc
                </button>
            </form>

            <a href="{{ route('admin.lessons.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-medium bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm">
                <i class="ri-add-line mr-2 text-sm"></i>
                Thêm bài học
            </a>
        </div>
    </div>

    {{-- Bảng bài học --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <span class="text-sm font-semibold text-slate-900">Danh sách bài học</span>
            @if(isset($lessons) && $lessons->count())
                <span class="text-xs text-slate-500">
                    Hiển thị {{ $lessons->firstItem() }}–{{ $lessons->lastItem() }} / {{ $lessons->total() }} bài học.
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Tiêu đề</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Khóa học</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">Chương</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Thời lượng</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Trạng thái</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Cập nhật</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lessons as $lesson)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-900">
                                        {{ $lesson->title }}
                                    </span>
                                    @if($lesson->previewable)
                                        <span class="text-[11px] text-emerald-600 mt-0.5 inline-flex items-center gap-1">
                                            <i class="ri-play-circle-line"></i> Cho phép xem thử
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 align-top hidden md:table-cell">
                                <span class="text-xs text-slate-700">
                                    {{ $lesson->course->title ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-top hidden lg:table-cell">
                                <span class="text-xs text-slate-700">
                                    {{ $lesson->section_title ?? $lesson->section?->title ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-top hidden md:table-cell">
                                <span class="text-xs text-slate-700">
                                    {{ $lesson->duration ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-top hidden md:table-cell">
                                @php $status = $lesson->status ?? 'draft'; @endphp
                                @if($status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Đã xuất bản
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] bg-amber-50 text-amber-600 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Nháp
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 align-top text-xs text-slate-500 hidden md:table-cell">
                                {{ optional($lesson->updated_at)->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="px-4 py-3 align-top text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 hover:bg-slate-50"
                                       title="Chỉnh sửa">
                                        <i class="ri-edit-line text-slate-500 text-lg"></i>
                                    </a>
                                    <form action="{{ route('admin.lessons.destroy', $lesson) }}"
                                          method="POST"
                                          onsubmit="return confirm('Xoá bài học này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 hover:bg-red-50"
                                                title="Xoá">
                                            <i class="ri-delete-bin-line text-red-500 text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-sm text-slate-500" colspan="7">
                                Chưa có bài học nào.
                                <a href="{{ route('admin.lessons.create') }}" class="text-indigo-600 font-medium">
                                    Thêm bài học đầu tiên?
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($lessons) && $lessons->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $lessons->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
