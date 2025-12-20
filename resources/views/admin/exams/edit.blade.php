@extends('admin.layout')

@section('title', 'Chỉnh sửa đề thi')
@section('page-title', 'Chỉnh sửa đề thi')
@section('page-description', 'Cập nhật thông tin đề thi')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Có lỗi xảy ra!</strong>
    <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Header with Back Button -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $exam->title }}</h3>
        <p class="text-sm text-gray-600">Chỉnh sửa thông tin đề thi</p>
    </div>
    
    <a href="{{ route('admin.exams.index') }}" 
       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
        <i data-feather="arrow-left" class="w-5 h-5"></i>
        Quay lại
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.exams.update', $exam) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Thông tin cơ bản -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tên đề thi <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title', $exam->title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Nhập tên đề thi...">
                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Nhập mô tả cho đề thi...">{{ old('description', $exam->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Môn học <span class="text-red-500">*</span></label>
                                <select id="subject" name="subject_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Chọn môn học</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
                                <select id="class" name="class_room_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Chọn lớp học</option>
                                    @foreach($classRooms as $classRoom)
                                        <option value="{{ $classRoom->id }}" 
                                                data-subject-id="{{ $classRoom->subject_id }}"
                                                {{ old('class_room_id', $exam->class_room_id) == $classRoom->id ? 'selected' : '' }}>
                                            {{ $classRoom->name }} ({{ $classRoom->subject ? $classRoom->subject->name : 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Loại đề thi <span class="text-red-500">*</span></label>
                                <select id="type" name="type" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="quiz" {{ old('type', $exam->type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="midterm" {{ old('type', $exam->type) == 'midterm' ? 'selected' : '' }}>Giữa kỳ</option>
                                    <option value="final" {{ old('type', $exam->type) == 'final' ? 'selected' : '' }}>Cuối kỳ</option>
                                    <option value="practice" {{ old('type', $exam->type) == 'practice' ? 'selected' : '' }}>Thực hành</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="draft" {{ old('status', $exam->status) == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="published" {{ old('status', $exam->status) == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="archived" {{ old('status', $exam->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thời gian -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thời gian</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Bắt đầu</label>
                            <input type="datetime-local" id="start_time" name="start_time"
                                   value="{{ old('start_time', $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Kết thúc</label>
                            <input type="datetime-local" id="end_time" name="end_time"
                                   value="{{ old('end_time', $exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Thời gian làm bài (phút) <span class="text-red-500">*</span></label>
                            <input type="number" id="duration" name="duration" min="1" required
                                   value="{{ old('duration', $exam->duration) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="60">
                            @error('duration')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="total_points" class="block text-sm font-medium text-gray-700 mb-1">Tổng điểm <span class="text-red-500">*</span></label>
                            <input type="number" id="total_points" name="total_points" min="0" step="0.5" required
                                   value="{{ old('total_points', $exam->total_points) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="100">
                            @error('total_points')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-1">Điểm đạt</label>
                            <input type="number" id="passing_score" name="passing_score" min="0" step="0.5"
                                   value="{{ old('passing_score', $exam->passing_score) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="50">
                        </div>
                    </div>
                </div>

                <!-- Cấu hình -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cấu hình</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="shuffle_questions" value="1" 
                                   {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Xáo trộn câu hỏi</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="shuffle_answers" value="1"
                                   {{ old('shuffle_answers', $exam->shuffle_answers) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Xáo trộn đáp án</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="show_results_immediately" value="1"
                                   {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Hiển thị kết quả sau khi nộp bài</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="allow_review" value="1"
                                   {{ old('allow_review', $exam->allow_review) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Cho phép xem lại bài thi</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t pt-6 flex items-center justify-between">
                    <button type="button" 
                            onclick="document.getElementById('deleteForm').submit()"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        Xóa đề thi
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.exams.index') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Hủy
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Separate Delete Form -->
<form id="deleteForm" action="{{ route('admin.exams.destroy', $exam) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject');
    const classSelect = document.getElementById('class');
    const allClassOptions = Array.from(classSelect.options);

    function filterClasses() {
        const selectedSubjectId = subjectSelect.value;
        
        // Remove all options except the first one (placeholder)
        classSelect.innerHTML = '';
        classSelect.add(allClassOptions[0].cloneNode(true));
        
        if (!selectedSubjectId) {
            // If no subject selected, show all classes
            allClassOptions.slice(1).forEach(option => {
                classSelect.add(option.cloneNode(true));
            });
        } else {
            // Filter classes by subject
            allClassOptions.slice(1).forEach(option => {
                if (option.dataset.subjectId === selectedSubjectId) {
                    classSelect.add(option.cloneNode(true));
                }
            });
        }
    }

    // Listen for subject change
    subjectSelect.addEventListener('change', function() {
        const currentClassValue = classSelect.value;
        filterClasses();
        
        // Try to restore the previously selected class if it's still available
        const optionExists = Array.from(classSelect.options).some(opt => opt.value === currentClassValue);
        if (optionExists) {
            classSelect.value = currentClassValue;
        } else {
            classSelect.value = '';
        }
    });

    // Initial filter on page load
    filterClasses();
    
    // Restore the selected class after filtering
    const initialClassValue = '{{ old('class_room_id', $exam->class_room_id) }}';
    if (initialClassValue) {
        classSelect.value = initialClassValue;
    }
    
    // Delete confirmation
    const deleteBtn = document.querySelector('button[onclick*="deleteForm"]');
    if (deleteBtn) {
        deleteBtn.onclick = function() {
            if (confirm('Bạn có chắc muốn xóa đề thi này? Hành động này không thể hoàn tác!')) {
                document.getElementById('deleteForm').submit();
            }
        };
    }
});
</script>

@endsection
