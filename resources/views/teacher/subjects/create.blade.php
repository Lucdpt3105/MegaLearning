@extends('layouts.app')

@section('title', 'Thêm Môn học Mới')

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
                <h1 class="text-3xl font-bold text-gray-900">Thêm Môn học Mới</h1>
                <p class="text-gray-600 mt-1">Tạo môn học mới trong hệ thống</p>
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
    <form action="{{ route('teacher.subjects.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
        @csrf

        <!-- Subject Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Tên môn học <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
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
                value="{{ old('code') }}"
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
            >{{ old('description') }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Trạng thái <span class="text-red-500">*</span>
            </label>
            <select 
                id="status" 
                name="status"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('status') border-red-500 @enderror"
                required
            >
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>📝 Nháp - Chưa công khai</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>✅ Hoạt động - Hiển thị cho học sinh</option>
                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>📦 Lưu trữ - Đã kết thúc</option>
            </select>
            @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Create Chat Room Option -->
        <div class="mb-8">
            <div class="flex items-start space-x-3 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                <input 
                    type="checkbox" 
                    id="create_chat_room" 
                    name="create_chat_room"
                    value="1"
                    {{ old('create_chat_room') ? 'checked' : '' }}
                    class="mt-1 w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                >
                <div class="flex-1">
                    <label for="create_chat_room" class="text-sm font-medium text-gray-900 cursor-pointer">
                        💬 Tự động tạo nhóm chat cho môn học
                    </label>
                    <p class="text-sm text-gray-600 mt-1">
                        Học sinh sẽ tự động được thêm vào nhóm chat khi đăng ký môn học này (UC-GV-015)
                    </p>
                </div>
            </div>
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
                <span>Tạo Môn học</span>
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
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">💡 Lưu ý:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Bạn có thể tạo môn học ở trạng thái "Nháp" và chuyển sang "Hoạt động" sau</li>
                    <li>Mã môn học không thể thay đổi sau khi tạo</li>
                    <li>Môn học ở trạng thái "Hoạt động" mới hiển thị cho học sinh</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
