@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('teacher.exams.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Tạo đề thi mới</h1>
            </div>
            <p class="text-gray-600">Nhập thông tin cơ bản cho đề thi. Bạn sẽ thêm câu hỏi ở bước tiếp theo.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('teacher.exams.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông tin cơ bản</h2>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tên đề thi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                               value="{{ old('title') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="VD: Kiểm tra giữa kỳ - Toán 10">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject and Class -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Môn học <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Chọn môn học</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Lớp học
                            </label>
                            <select name="class_room_id" id="class_room_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tất cả lớp</option>
                                @foreach($classRooms as $classRoom)
                                    <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                        {{ $classRoom->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_room_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Type and Duration -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại đề thi <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Chọn loại</option>
                                <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Kiểm tra</option>
                                <option value="midterm" {{ old('type') == 'midterm' ? 'selected' : '' }}>Giữa kỳ</option>
                                <option value="final" {{ old('type') == 'final' ? 'selected' : '' }}>Cuối kỳ</option>
                                <option value="practice" {{ old('type') == 'practice' ? 'selected' : '' }}>Luyện tập</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Thời gian (phút) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="duration" id="duration" required min="1"
                                   value="{{ old('duration', 60) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Mô tả
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Mô tả chi tiết về đề thi...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Grading Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thiết lập điểm</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="total_points" class="block text-sm font-medium text-gray-700 mb-2">
                            Tổng điểm <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_points" id="total_points" required min="0" step="0.1"
                               value="{{ old('total_points', 10) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('total_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">
                            Điểm đạt <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="passing_score" id="passing_score" required min="0" step="0.1"
                               value="{{ old('passing_score', 5) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('passing_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Lịch thi</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian bắt đầu
                        </label>
                        <input type="datetime-local" name="start_time" id="start_time"
                               value="{{ old('start_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian kết thúc
                        </label>
                        <input type="datetime-local" name="end_time" id="end_time"
                               value="{{ old('end_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">Để trống nếu muốn học sinh có thể làm bất cứ lúc nào</p>
            </div>

            <!-- Advanced Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thiết lập nâng cao</h2>
                
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="shuffle_questions" value="1" 
                               {{ old('shuffle_questions') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <div class="font-medium text-gray-900">Xáo trộn câu hỏi</div>
                            <div class="text-sm text-gray-500">Thứ tự câu hỏi sẽ khác nhau với mỗi học sinh</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="shuffle_answers" value="1"
                               {{ old('shuffle_answers') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <div class="font-medium text-gray-900">Xáo trộn đáp án</div>
                            <div class="text-sm text-gray-500">Thứ tự đáp án sẽ khác nhau với mỗi học sinh</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="show_results_immediately" value="1" checked
                               {{ old('show_results_immediately', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <div class="font-medium text-gray-900">Hiển thị kết quả ngay</div>
                            <div class="text-sm text-gray-500">Học sinh thấy điểm ngay sau khi nộp bài</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="allow_review" value="1" checked
                               {{ old('allow_review', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <div class="font-medium text-gray-900">Cho phép xem lại</div>
                            <div class="text-sm text-gray-500">Học sinh có thể xem lại bài làm và đáp án</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-6">
                <a href="{{ route('teacher.exams.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tạo đề thi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
