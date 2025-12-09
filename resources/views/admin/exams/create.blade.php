@extends('admin.layout')

@section('title', 'Tạo đề thi mới')
@section('page-title', 'Tạo đề thi mới')
@section('page-description', 'Tạo và cấu hình đề thi cho lớp học')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="#" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Thông tin cơ bản -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tên đề thi <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Nhập tên đề thi...">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Nhập mô tả cho đề thi..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Môn học <span class="text-red-500">*</span></label>
                                <select id="subject" name="subject_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Chọn môn học</option>
                                </select>
                            </div>

                            <div>
                                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Lớp học <span class="text-red-500">*</span></label>
                                <select id="class" name="class_room_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Chọn lớp học</option>
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
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Bắt đầu <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="start_time" name="start_time" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Kết thúc <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="end_time" name="end_time" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Thời gian làm bài (phút) <span class="text-red-500">*</span></label>
                            <input type="number" id="duration" name="duration" min="1" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="60">
                        </div>

                        <div>
                            <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-1">Số lần làm tối đa</label>
                            <input type="number" id="max_attempts" name="max_attempts" min="1" value="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Cấu hình -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cấu hình</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="shuffle_questions" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Xáo trộn câu hỏi</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="show_results" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Hiển thị kết quả sau khi nộp bài</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="allow_review" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Cho phép xem lại bài thi</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.exams.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                        Tạo đề thi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
