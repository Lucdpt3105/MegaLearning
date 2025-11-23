@extends('layouts.app')

@section('title', 'Chỉnh sửa Môn học')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.subjects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa Môn học</h1>
                <p class="text-gray-600 mt-1">UC-GV-012: Cập nhật thông tin môn học</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-red-800 font-medium">Có lỗi xảy ra:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('teacher.subjects.update', $subject) }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
        @csrf
        @method('PUT')

        <!-- Subject Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Tên môn học <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $subject->name) }}"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                placeholder="VD: Giải tích 1, Lập trình Web..."
                required
            >
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subject Code -->
        <div class="mb-6">
            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                Mã môn học <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="code" 
                name="code" 
                value="{{ old('code', $subject->code) }}"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('code') border-red-500 @enderror"
                placeholder="VD: MATH101, CS201..."
                required
            >
            @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Mã môn học phải là duy nhất trong hệ thống</p>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Mô tả môn học
            </label>
            <textarea 
                id="description" 
                name="description" 
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                placeholder="Nhập mô tả chi tiết về môn học..."
            >{{ old('description', $subject->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-8">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Trạng thái <span class="text-red-500">*</span>
            </label>
            <select 
                id="status" 
                name="status"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('status') border-red-500 @enderror"
                required
            >
                <option value="draft" {{ old('status', $subject->status) === 'draft' ? 'selected' : '' }}>📝 Nháp - Chưa công khai</option>
                <option value="active" {{ old('status', $subject->status) === 'active' ? 'selected' : '' }}>✅ Hoạt động - Hiển thị cho học sinh</option>
                <option value="archived" {{ old('status', $subject->status) === 'archived' ? 'selected' : '' }}>📦 Lưu trữ - Đã kết thúc</option>
            </select>
            @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-4">
            <button 
                type="submit"
                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center justify-center space-x-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Lưu thay đổi</span>
            </button>
            
            <a 
                href="{{ route('teacher.subjects.index') }}"
                class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
            >
                Hủy bỏ
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="text-sm text-yellow-800">
                <p class="font-medium mb-1">⚠️ Lưu ý khi chỉnh sửa:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Thay đổi trạng thái có thể ảnh hưởng đến khả năng truy cập của học sinh</li>
                    <li>Nếu chuyển sang "Lưu trữ", học sinh sẽ không thể đăng ký môn học này nữa</li>
                    <li>Dữ liệu liên quan (lớp học, đề thi, tài liệu) sẽ không bị ảnh hưởng</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
