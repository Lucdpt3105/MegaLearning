@extends('admin.layout')

@section('title', 'Chỉnh sửa khóa học')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa khóa học</h1>
                <p class="text-gray-600 mt-1">Cập nhật thông tin khóa học: {{ $course->name }}</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i data-feather="arrow-left" class="w-4 h-4 inline"></i> Quay lại
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tên khóa học --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên khóa học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $course->name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                        placeholder="Ví dụ: Toán học cơ bản lớp 10"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mã khóa học --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mã khóa học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $course->code) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" 
                        placeholder="Ví dụ: MATH10-2025"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Mã duy nhất để định danh khóa học</p>
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
                            <option value="{{ $subject->id }}" {{ old('subject_id', $course->subject_id) == $subject->id ? 'selected' : '' }}>
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
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
                    <input type="number" name="max_students" value="{{ old('max_students', $course->max_students) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_students') border-red-500 @enderror" 
                        min="1" max="100" required>
                    @error('max_students')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Trạng thái <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                        required>
                        <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="closed" {{ old('status', $course->status) == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày bắt đầu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày bắt đầu <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', $course->start_date) }}" 
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
                    <input type="date" name="end_date" value="{{ old('end_date', $course->end_date) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả khóa học
                    </label>
                    <textarea name="description" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                        placeholder="Nhập mô tả chi tiết về khóa học...">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Stats Info --}}
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Thông tin khóa học</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-blue-600">Học viên đã đăng ký:</span>
                        <span class="font-semibold text-blue-900 ml-1">{{ $course->enrollments_count ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-blue-600">Ngày tạo:</span>
                        <span class="font-semibold text-blue-900 ml-1">{{ $course->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-blue-600">Cập nhật:</span>
                        <span class="font-semibold text-blue-900 ml-1">{{ $course->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 mt-6 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i data-feather="save" class="w-4 h-4 inline"></i> Cập nhật khóa học
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
