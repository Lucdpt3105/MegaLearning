@extends('admin.layout')

@section('title', 'Thêm môn học')
@section('page-title', 'Thêm môn học mới')
@section('page-description', 'Tạo một môn học mới trong hệ thống')

@section('content')
    <div class="max-w-3xl">
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Back Button -->
        <a href="{{ route('admin.subjects.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
            <span class="mr-2">←</span>
            Quay lại danh sách
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                
                <!-- Subject Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên môn học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           placeholder="Ví dụ: Lập Trình Web"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-gray-500">Nhập tên môn học rõ ràng và dễ hiểu</p>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Code (Optional) -->
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Mã môn học <span class="text-gray-400">(Tùy chọn)</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code') }}"
                           placeholder="Ví dụ: WEB101"
                           maxlength="20"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('code') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-gray-500">Để trống nếu muốn tự động tạo mã</p>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả <span class="text-gray-400">(Tùy chọn)</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Nhập mô tả chi tiết về môn học..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teacher (Optional) -->
                <div class="mb-6">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Giáo viên phụ trách <span class="text-gray-400">(Tùy chọn)</span>
                    </label>
                    <select id="teacher_id" 
                            name="teacher_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('teacher_id') border-red-500 @enderror">
                        <option value="">-- Chưa chọn giáo viên --</option>
                        @foreach(\App\Models\User::role('teacher')->get() as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Có thể gán giáo viên sau khi tạo môn học</p>
                    @error('teacher_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.subjects.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Tạo môn học
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
