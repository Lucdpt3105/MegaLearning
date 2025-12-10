@extends('admin.layout')

@section('title', 'Thêm khóa học')
@section('page-title', 'Thêm khóa học mới')
@section('page-description', 'Tạo khóa học mới với thông tin chi tiết, nội dung và cấu hình hiển thị.')

@section('content')
<form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    {{-- Cột trái: nội dung chính --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Thông tin cơ bản --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="ri-information-line text-indigo-500 text-lg"></i>
                <h2 class="text-sm font-semibold text-slate-900">Thông tin cơ bản</h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Tên khóa học <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Nhập tên khóa học">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Mô tả ngắn
                    </label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}"
                           class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Mô tả ngắn hiển thị ở danh sách khóa học">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Mô tả chi tiết
                    </label>
                    <textarea name="description" rows="6"
                              class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Mô tả chi tiết về nội dung, cấu trúc và mục tiêu khóa học">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Danh mục <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id"
                                class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Chọn danh mục</option>
                            @foreach($allCategories ?? [] as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Cấp độ
                        </label>
                        <select name="level"
                                class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="beginner" @selected(old('level') === 'beginner')>Cơ bản</option>
                            <option value="intermediate" @selected(old('level') === 'intermediate')>Trung cấp</option>
                            <option value="advanced" @selected(old('level') === 'advanced')>Nâng cao</option>
                            <option value="all" @selected(old('level') === 'all')>Tất cả cấp độ</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nội dung khóa học (demo tĩnh, m sau này gắn JS hoặc CRUD Section/Lesson riêng) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ri-list-check-2 text-indigo-500 text-lg"></i>
                    <h2 class="text-sm font-semibold text-slate-900">Nội dung khóa học</h2>
                </div>
                <button type="button"
                        class="inline-flex items-center rounded-xl border border-indigo-200 text-indigo-600 text-xs font-medium px-3 py-1.5 hover:bg-indigo-50">
                    <i class="ri-add-line mr-1 text-sm"></i> Thêm chương (demo)
                </button>
            </div>
            <div class="p-5 space-y-4 text-xs text-slate-500">
                <p>
                    Khu vực này mày có thể:
                    <br>• Hoặc để tĩnh mô tả cấu trúc chương – bài học,
                    <br>• Hoặc sau này làm riêng module quản lý Chương/Bài học rồi chỉ hiển thị danh sách.
                </p>

                <div class="border rounded-2xl p-3">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                            <i class="ri-folder-line text-indigo-500"></i>
                            Chương 1: Giới thiệu (ví dụ)
                        </h3>
                        <div class="flex gap-2">
                            <button type="button"
                                    class="w-8 h-8 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50">
                                <i class="ri-edit-line text-slate-500 text-sm"></i>
                            </button>
                            <button type="button"
                                    class="w-8 h-8 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-rose-50">
                                <i class="ri-delete-bin-line text-rose-500 text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="ri-play-circle-line text-indigo-500"></i>
                                Bài 1: Tổng quan khóa học
                            </span>
                            <span class="text-slate-400">10:00</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="ri-file-text-line text-sky-500"></i>
                                Bài 2: Tài liệu giới thiệu
                            </span>
                            <span class="text-slate-400">PDF</span>
                        </li>
                    </ul>

                    <button type="button"
                            class="mt-3 inline-flex items-center rounded-xl border border-emerald-200 text-emerald-600 text-xs font-medium px-3 py-1.5 hover:bg-emerald-50">
                        <i class="ri-add-line mr-1 text-sm"></i> Thêm bài học (demo)
                    </button>
                </div>
            </div>
        </div>

        {{-- Yêu cầu & mục tiêu --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="ri-task-line text-indigo-500 text-lg"></i>
                <h2 class="text-sm font-semibold text-slate-900">Yêu cầu & Mục tiêu</h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Yêu cầu tiên quyết
                    </label>
                    <textarea name="requirements" rows="3"
                              class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Học viên cần biết gì trước khi tham gia? (mỗi yêu cầu một dòng)">{{ old('requirements') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Mục tiêu học tập
                    </label>
                    <textarea name="objectives" rows="3"
                              class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Sau khóa học, học viên sẽ đạt được gì?">{{ old('objectives') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Cột phải: cấu hình --}}
    <div class="space-y-6">
        {{-- Trạng thái & hiển thị --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">Trạng thái & hiển thị</h2>
            </div>
            <div class="p-5 space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Trạng thái</label>
                    <select name="status"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="draft" @selected(old('status') === 'draft')>Nháp</option>
                        <option value="active" @selected(old('status') === 'active')>Đang hoạt động</option>
                        <option value="pending" @selected(old('status') === 'pending')>Chờ duyệt</option>
                        <option value="hidden" @selected(old('status') === 'hidden')>Tạm ẩn</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Ngày mở đăng ký</label>
                    <input type="date" name="enroll_open_at" value="{{ old('enroll_open_at') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Ngày kết thúc</label>
                    <input type="date" name="enroll_close_at" value="{{ old('enroll_close_at') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
            </div>
        </div>

        {{-- Ảnh & giá --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">Ảnh bìa & Giá</h2>
            </div>
            <div class="p-5 space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Ảnh bìa khóa học</label>
                    <input type="file" name="thumbnail"
                           class="w-full text-xs text-slate-600 file:mr-3 file:px-3 file:py-1.5 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    <p class="mt-1 text-[11px] text-slate-400">
                        Đề xuất 800x400, dung lượng &lt; 2MB.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Giá (VNĐ)</label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                               placeholder="vd: 499000">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Giá khuyến mãi</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                               placeholder="vd: 299000">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input id="is_free" type="checkbox" name="is_free" value="1"
                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                           @checked(old('is_free'))>
                    <label for="is_free" class="text-xs text-slate-700">Khóa học miễn phí</label>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">SEO & Tag</h2>
            </div>
            <div class="p-5 space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Slug (URL)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                           placeholder="vd: lap-trinh-python-co-ban">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Từ khóa (tags)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                           placeholder="vd: python, beginner, web">
                    <p class="mt-1 text-[11px] text-slate-400">Ngăn cách bằng dấu phẩy (,).</p>
                </div>
            </div>
        </div>

        {{-- Nút lưu --}}
        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full rounded-xl bg-indigo-600 text-white text-sm font-medium px-4 py-2.5 hover:bg-indigo-700">
                <i class="ri-save-line mr-1"></i> Lưu khóa học
            </button>
            <a href="{{ route('admin.courses.index') }}"
               class="w-full rounded-xl border border-slate-200 text-xs text-slate-600 px-4 py-2.5 text-center hover:bg-slate-50">
                Hủy và quay lại danh sách
            </a>
        </div>
    </div>
</form>
@endsection
