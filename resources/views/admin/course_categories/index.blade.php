@extends('admin.layout')

@section('title', 'Danh mục khóa học')
@section('page-title', 'Danh mục khóa học')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">

    {{-- BÊN TRÁI: DANH MỤC --}}
    <div class="flex-1 space-y-4">

        {{-- STATS HÀNG TRÊN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Tổng danh mục</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ $stats['total_categories'] }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center">
                    <i class="ri-folder-2-line text-indigo-600 text-lg"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Đang hoạt động</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ $stats['active_categories'] }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-emerald-600 text-lg"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Tổng khóa học</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ $stats['total_courses'] }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-violet-50 flex items-center justify-center">
                    <i class="ri-book-open-line text-violet-600 text-lg"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Danh mục phụ</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ $stats['total_subcategories'] ?? 0 }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center">
                    <i class="ri-node-tree text-slate-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- HEADER + NÚT TẠO --}}
        <div class="flex items-center justify-between mt-6">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Danh sách danh mục</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Quản lý các nhóm môn học, ví dụ: Toán, Lập trình, Tiếng Anh,...
                </p>
            </div>
            <button
                x-data
                @click="$dispatch('open-category-modal')"
                class="px-4 py-2 rounded-2xl text-xs font-medium bg-indigo-600 text-white shadow-sm hover:bg-indigo-500">
                + Thêm danh mục
            </button>
        </div>

        {{-- DANH MỤC --}}
        <div class="space-y-3">
            @forelse($categories as $category)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <i class="ri-folder-2-line text-indigo-600 text-lg"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-sm text-slate-900">{{ $category->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $category->courses_count }} khóa học
                            </p>

                            {{-- Các khóa học ví dụ: Toán Đại Cương, Toán Tuyến Tính,... --}}
                            @if($category->courses_count)
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($category->courses->take(3) as $course)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-slate-100 text-[11px] text-slate-700">
                                            <i class="ri-book-open-line text-xs mr-1"></i>
                                            {{ $course->name }}
                                        </span>
                                    @endforeach

                                    @if($category->courses_count > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-slate-100 text-[11px] text-slate-500">
                                            +{{ $category->courses_count - 3 }} khóa khác
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Nút thêm khóa học thuộc danh mục này --}}
                            <div class="mt-3">
                                <a href="{{ route('admin.courses.create', ['subject_id' => $category->id]) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-xl border border-indigo-200 text-[11px] text-indigo-600 bg-indigo-50/40 hover:bg-indigo-50">
                                    <i class="ri-add-line text-xs mr-1"></i>
                                    Thêm khóa học
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end md:self-auto">
    {{-- Trạng thái --}}
    @if($category->status === 'active')
        <span class="px-3 py-1 rounded-full text-[11px] bg-emerald-50 text-emerald-600 border border-emerald-200">
            Hoạt động
        </span>
    @elseif($category->status === 'draft')
        <span class="px-3 py-1 rounded-full text-[11px] bg-amber-50 text-amber-600 border border-amber-200">
            Nháp
        </span>
    @else
        <span class="px-3 py-1 rounded-full text-[11px] bg-slate-100 text-slate-600 border border-slate-200">
            Ẩn
        </span>
    @endif

    {{-- Nút + (sửa / xem chi tiết) --}}
    <a href="{{ route('admin.course-categories.edit', $category->id) }}"
       class="w-8 h-8 flex items-center justify-center rounded-full border border-sky-400 bg-sky-50 text-sky-500 text-lg leading-none hover:bg-sky-100">
        +
    </a>

    {{-- Nút - (xoá) --}}
    <form action="{{ route('admin.course-categories.destroy', $category->id) }}"
          method="POST"
          onsubmit="return confirm('Xoá danh mục này?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-8 h-8 flex items-center justify-center rounded-full border border-red-400 bg-red-50 text-red-500 text-lg leading-none hover:bg-red-100">
            –
        </button>
    </form>
</div>

                </div>
            @empty
                <p class="text-sm text-slate-500">Chưa có danh mục nào.</p>
            @endforelse
        </div>
    </div>

    {{-- BÊN PHẢI --}}
    <div class="w-full lg:w-80 space-y-4">

        {{-- Danh mục phổ biến (card trắng giống dashboard) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <h3 class="text-sm font-semibold text-slate-900 mb-3">Danh mục phổ biến</h3>
            <div class="space-y-3">
                @foreach(($stats['popular'] ?? []) as $cat)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-slate-800">{{ $cat->name }}</span>
                            <span class="text-slate-500">{{ $cat->courses_count }} khóa</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-500"
                                 style="width: {{ min(100, $cat->courses_count * 10) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- THAO TÁC NHANH: dùng gradient giống dashboard --}}
        <div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl shadow-sm text-white p-4">
            <h2 class="text-sm font-semibold mb-3">Thao tác nhanh</h2>

            <div class="space-y-2 text-sm">
                <button @click="$dispatch('open-category-modal')"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg:white/20 hover:bg-white/15 transition">
                    <span>Thêm danh mục mới</span>
                    <i class="ri-add-line text-base"></i>
                </button>

                <a href="{{ route('admin.courses.index') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                    <span>Danh sách khóa học</span>
                    <i class="ri-arrow-right-line text-base"></i>
                </a>

                <a href="{{ route('admin.courses.create') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                    <span>Tạo khóa học mới</span>
                    <i class="ri-edit-box-line text-base"></i>
                </a>
            </div>
        </div>
    </div>

</div>

{{-- MODAL THÊM DANH MỤC --}}
<div x-data="{ open:false }"
     x-on:open-category-modal.window="open = true"
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
     style="display:none;">
    <div @click.away="open=false"
         class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-slate-900">Thêm danh mục mới</h2>
            <button @click="open=false"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i class="ri-close-line text-lg text-slate-500"></i>
            </button>
        </div>

        <form action="{{ route('admin.course-categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs mb-1 text-slate-600">Tên danh mục</label>
                <input type="text" name="name"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs mb-1 text-slate-600">Mô tả</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div>
                <label class="block text-xs mb-1 text-slate-600">Trạng thái</label>
                <select name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Hoạt động</option>
                    <option value="draft">Nháp</option>
                    <option value="archived">Lưu trữ</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="open=false"
                        class="px-4 py-2 text-xs rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Hủy
                </button>
                <button type="submit"
                        class="px-4 py-2 text-xs rounded-xl bg-indigo-600 text-white hover:bg-indigo-500">
                    Thêm danh mục
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
