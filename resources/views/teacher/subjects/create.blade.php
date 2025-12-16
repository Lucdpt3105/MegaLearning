@extends('layouts.app')

@section('title', 'Thêm Môn học')
@section('page-title', 'Thêm Môn học Mới')
@section('page-description', 'Tạo môn học / danh mục mới trong hệ thống khoá học.')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('teacher.subjects.index') }}"
               class="text-slate-500 hover:text-slate-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Thêm Môn học Mới</h1>
                <p class="text-sm text-slate-500 mt-1">Tạo môn học (subject) mới cho hệ thống</p>
            </div>
        </div>
    </div>

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-rose-700">Có lỗi xảy ra:</h3>
                    <ul class="mt-1 text-xs text-rose-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('teacher.subjects.store') }}" method="POST"
          class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 space-y-6">
        @csrf

        {{-- Tên môn học --}}
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                Tên môn học <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="w-full rounded-xl border text-sm px-4 py-2.5
                       border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       @error('name') border-rose-400 focus:ring-rose-500 @enderror"
                placeholder="VD: Giải tích 1, Lập trình Web..."
                required
            >
            @error('name')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mã môn học --}}
        <div>
            <label for="code" class="block text-sm font-medium text-slate-700 mb-2">
                Mã môn học
            </label>
            <input
                type="text"
                id="code"
                name="code"
                value="{{ old('code') }}"
                class="w-full rounded-xl border text-sm px-4 py-2.5
                       border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       @error('code') border-rose-400 focus:ring-rose-500 @enderror"
                placeholder="VD: MATH101, CS201..."
            >
            @error('code')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-slate-500">
                Nếu để trống, hệ thống sẽ tự sinh mã từ tên môn học. Mã phải là duy nhất.
            </p>
        </div>

        {{-- Mô tả --}}
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                Mô tả môn học
            </label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="w-full rounded-xl border text-sm px-4 py-2.5
                       border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       @error('description') border-rose-400 focus:ring-rose-500 @enderror"
                placeholder="Nhập mô tả chi tiết về môn học..."
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                Trạng thái <span class="text-rose-500">*</span>
            </label>
            <select
                id="status"
                name="status"
                class="w-full rounded-xl border text-sm px-4 py-2.5
                       border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       @error('status') border-rose-400 focus:ring-rose-500 @enderror"
                required
            >
                <option value="draft"   {{ old('status') === 'draft'   ? 'selected' : '' }}>📝 Nháp - Chưa công khai</option>
                <option value="active"  {{ old('status') === 'active'  ? 'selected' : '' }}>✅ Hoạt động - Hiển thị cho học sinh</option>
                <option value="archived"{{ old('status') === 'archived'? 'selected' : '' }}>📦 Lưu trữ - Đã kết thúc</option>
            </select>
            @error('status')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- (Option) Tự tạo nhóm chat – nếu muốn dùng sau này --}}
        <div class="pt-2">
            <div class="flex items-start gap-3 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                <input
                    type="checkbox"
                    id="create_chat_room"
                    name="create_chat_room"
                    value="1"
                    {{ old('create_chat_room') ? 'checked' : '' }}
                    class="mt-1 w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
                >
            <div class="flex-1">
                <label for="create_chat_room" class="text-sm font-medium text-slate-900 cursor-pointer">
                    💬 Tự động tạo nhóm chat cho môn học
                </label>
                <p class="text-xs text-slate-600 mt-1">
                    Học sinh sẽ được thêm vào nhóm chat khi đăng ký môn học này (tùy chọn, m có thể xử lý logic sau).
                </p>
            </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2
                           rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                           px-6 py-2.5 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Tạo Môn học</span>
            </button>

            <a href="{{ route('teacher.subjects.index') }}"
               class="px-6 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                Hủy bỏ
            </a>
        </div>
    </form>

    {{-- Info box --}}
    <div class="mt-6 bg-sky-50 border border-sky-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-sky-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-sky-800 space-y-1">
                <p class="font-semibold">💡 Lưu ý:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Có thể tạo môn học ở trạng thái <strong>Nháp</strong> rồi chuyển sang <strong>Hoạt động</strong> sau.</li>
                    <li>Nếu không nhập mã, hệ thống sẽ tự sinh từ tên môn học (ví dụ: <code>GIAI_TICH_1</code>).</li>
                    <li>Môn học ở trạng thái <strong>Hoạt động</strong> mới hiển thị cho học sinh / giáo viên.</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
