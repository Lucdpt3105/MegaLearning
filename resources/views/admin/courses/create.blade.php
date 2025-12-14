@extends('admin.layout')

@section('title', 'Thêm lớp học mới')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Thêm lớp học mới</h1>
                <p class="text-gray-600 mt-1">Tạo lớp học mới cho học sinh</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i data-feather="arrow-left" class="w-4 h-4 inline"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.courses.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tên lớp học --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên lớp học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                        placeholder="Ví dụ: Toán học cơ bản lớp 10"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mã lớp học --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mã lớp học <span class="text-gray-400">(Tùy chọn - Tự động tạo nếu để trống)</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" 
                        placeholder="Ví dụ: MATH10-2025">
                    <p class="mt-1 text-xs text-gray-500">Mã duy nhất để định danh lớp học</p>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Môn học --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Môn học <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject_id') border-red-500 @enderror" 
                        required>
                        <option value="">-- Chọn môn học --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giáo viên --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Giáo viên phụ trách <span class="text-red-500">*</span>
                    </label>
                    <select name="teacher_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('teacher_id') border-red-500 @enderror" 
                        required>
                        <option value="">-- Chọn giáo viên --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Số lượng học sinh tối đa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Số lượng tối đa <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="max_students" value="{{ old('max_students', 30) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_students') border-red-500 @enderror" 
                        min="1" max="100" required>
                    @error('max_students')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày bắt đầu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày bắt đầu <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror" 
                        required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày kết thúc --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày kết thúc
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả lớp học
                    </label>
                    <textarea name="description" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Nhập mô tả chi tiết về lớp học...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 mt-6 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i data-feather="save" class="w-4 h-4 inline"></i> Lưu lớp học
                </button>
                <a href="{{ route('admin.courses.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Initialize Feather Icons
    if (window.feather) {
        feather.replace();
    }
</script>
@endsection
