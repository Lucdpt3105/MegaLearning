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
                                    <option value="{{ $subject->id }}" {{ (old('subject_id') ?? request('subject_id')) == $subject->id ? 'selected' : '' }}>
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
                                Lớp học <span class="text-red-500">*</span>
                            </label>
                            <select name="class_room_id" id="class_room_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Chọn lớp học</option>
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

            <!-- Security Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Cài đặt bảo mật 🔥
                </h2>
                
                <div class="space-y-4">
                    <!-- Access Code -->
                    <div class="border-l-4 border-red-500 pl-4">
                        <label class="flex items-start gap-3 cursor-pointer mb-3">
                            <input type="checkbox" name="require_access_code" id="require_access_code" value="1" 
                                   {{ old('require_access_code') ? 'checked' : '' }}
                                   onchange="document.getElementById('access_code_input').classList.toggle('hidden')"
                                   class="w-5 h-5 mt-1 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <div>
                                <div class="font-medium text-gray-900">Yêu cầu mã truy cập</div>
                                <div class="text-sm text-gray-500">Học sinh phải nhập mã để vào làm bài</div>
                            </div>
                        </label>
                        <div id="access_code_input" class="ml-8 {{ old('require_access_code') ? '' : 'hidden' }}">
                            <input type="text" name="access_code" id="access_code"
                                   value="{{ old('access_code', strtoupper(Str::random(6))) }}"
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent font-mono text-lg">
                            <p class="mt-1 text-xs text-gray-500">Mã này sẽ được tạo tự động hoặc bạn có thể tự đặt</p>
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="restrict_to_class" value="1" checked
                               {{ old('restrict_to_class', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <div class="font-medium text-gray-900">Chỉ học sinh trong lớp</div>
                            <div class="text-sm text-gray-500">Chỉ học sinh đã đăng ký lớp mới được làm bài</div>
                        </div>
                    </label>

                    <!-- Cheating Detection -->
                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">🚨 Chống gian lận</h3>
                        
                        <label class="flex items-start gap-3 cursor-pointer mb-3">
                            <input type="checkbox" name="detect_cheating" id="detect_cheating" value="1" 
                                   {{ old('detect_cheating') ? 'checked' : '' }}
                                   onchange="toggleCheatingOptions()"
                                   class="w-5 h-5 mt-1 text-orange-600 rounded focus:ring-2 focus:ring-orange-500">
                            <div>
                                <div class="font-medium text-gray-900">Bật phát hiện gian lận</div>
                                <div class="text-sm text-gray-500">Kích hoạt các tính năng giám sát</div>
                            </div>
                        </label>

                        <div id="cheating_options" class="ml-8 space-y-3 {{ old('detect_cheating') ? '' : 'hidden' }}">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="detect_tab_switch" value="1"
                                       {{ old('detect_tab_switch') ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 rounded">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">Phát hiện đổi tab</div>
                                    <div class="text-gray-500">Cảnh báo khi học sinh chuyển sang tab khác</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="detect_device_change" value="1"
                                       {{ old('detect_device_change') ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 rounded">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">Phát hiện đổi thiết bị</div>
                                    <div class="text-gray-500">Khóa bài làm nếu đổi máy tính/điện thoại</div>
                                </div>
                            </label>

                            <div class="border-l-2 border-orange-300 pl-3">
                                <label class="flex items-start gap-3 cursor-pointer mb-2">
                                    <input type="checkbox" name="lock_on_exit" id="lock_on_exit" value="1"
                                           {{ old('lock_on_exit') ? 'checked' : '' }}
                                           onchange="document.getElementById('exit_time_input').classList.toggle('hidden')"
                                           class="w-4 h-4 mt-1 text-red-600 rounded">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">Khóa khi thoát ứng dụng</div>
                                        <div class="text-gray-500">Tự động khóa nếu học sinh thoát quá lâu</div>
                                    </div>
                                </label>
                                <div id="exit_time_input" class="ml-7 {{ old('lock_on_exit') ? '' : 'hidden' }}">
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="max_exit_time" min="5" max="300" step="5"
                                               value="{{ old('max_exit_time', 30) }}"
                                               class="w-24 px-3 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-sm text-gray-600">giây</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Thời gian tối đa được thoát (5-300 giây)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Monitoring (Optional) -->
                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">📹 Giám sát nâng cao (Tùy chọn)</h3>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="require_camera" value="1"
                                   {{ old('require_camera') ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500">
                            <div>
                                <div class="font-medium text-gray-900">Bật camera</div>
                                <div class="text-sm text-gray-500">Yêu cầu học sinh bật camera trong khi làm bài</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer mt-3">
                            <input type="checkbox" name="require_screen_recording" value="1"
                                   {{ old('require_screen_recording') ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500">
                            <div>
                                <div class="font-medium text-gray-900">Ghi màn hình</div>
                                <div class="text-sm text-gray-500">Ghi lại hoạt động trên màn hình của học sinh</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Auto Generate Section -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-sm p-6 border-2 border-green-200">
                <div class="flex items-start gap-3 mb-6">
                    <input type="checkbox" name="auto_generate" id="auto_generate" value="1"
                           {{ old('auto_generate') ? 'checked' : '' }}
                           onchange="toggleAutoGenerate()"
                           class="w-6 h-6 mt-1 text-green-600 rounded focus:ring-2 focus:ring-green-500">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Tạo đề tự động 🔥
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Hệ thống sẽ tự động chọn câu hỏi từ ngân hàng theo tiêu chí của bạn</p>
                    </div>
                </div>

                <div id="auto_generate_options" class="{{ old('auto_generate') ? '' : 'hidden' }}">
                    <!-- Question Distribution by Difficulty -->
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <h3 class="font-semibold text-gray-900 mb-3">📊 Phân bổ theo mức độ</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nhận biết (Bloom Level 1)
                                </label>
                                <input type="number" name="auto_gen_level_1" min="0" value="{{ old('auto_gen_level_1', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Thông hiểu (Bloom Level 2)
                                </label>
                                <input type="number" name="auto_gen_level_2" min="0" value="{{ old('auto_gen_level_2', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Vận dụng (Bloom Level 3)
                                </label>
                                <input type="number" name="auto_gen_level_3" min="0" value="{{ old('auto_gen_level_3', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Vận dụng cao (Bloom Level 4+)
                                </label>
                                <input type="number" name="auto_gen_level_4" min="0" value="{{ old('auto_gen_level_4', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Question Type Distribution -->
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <h3 class="font-semibold text-gray-900 mb-3">📝 Phân bổ theo loại câu hỏi</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Trắc nghiệm
                                </label>
                                <input type="number" name="auto_gen_multiple_choice" min="0" value="{{ old('auto_gen_multiple_choice', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tự luận
                                </label>
                                <input type="number" name="auto_gen_essay" min="0" value="{{ old('auto_gen_essay', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Topic Selection -->
                    <div class="bg-white rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">📚 Chọn chương/bài</h3>
                        <select name="auto_gen_topics[]" id="auto_gen_topics" multiple size="5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">Tất cả chương/bài</option>
                            <!-- Will be populated via AJAX based on selected subject -->
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Giữ Ctrl (Cmd) để chọn nhiều chương/bài. Để trống = chọn tất cả</p>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <strong>Lưu ý:</strong> Nếu bật tạo tự động, các câu hỏi sẽ được chọn ngẫu nhiên từ ngân hàng theo tiêu chí trên. Bạn vẫn có thể chỉnh sửa sau khi tạo.
                            </div>
                        </div>
                    </div>
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

<script>
function toggleCheatingOptions() {
    const checkbox = document.getElementById('detect_cheating');
    const options = document.getElementById('cheating_options');
    if (checkbox.checked) {
        options.classList.remove('hidden');
    } else {
        options.classList.add('hidden');
    }
}

function toggleAutoGenerate() {
    const checkbox = document.getElementById('auto_generate');
    const options = document.getElementById('auto_generate_options');
    if (checkbox.checked) {
        options.classList.remove('hidden');
        loadTopicsForSubject();
    } else {
        options.classList.add('hidden');
    }
}

// Load topics when subject changes
document.getElementById('subject_id').addEventListener('change', function() {
    if (document.getElementById('auto_generate').checked) {
        loadTopicsForSubject();
    }
});

function loadTopicsForSubject() {
    const subjectId = document.getElementById('subject_id').value;
    const topicsSelect = document.getElementById('auto_gen_topics');
    
    if (!subjectId) {
        topicsSelect.innerHTML = '<option value="">Chọn môn học trước</option>';
        return;
    }
    
    // Show loading
    topicsSelect.innerHTML = '<option value="">Đang tải...</option>';
    
    // Fetch topics
    fetch(`/teacher/subjects/${subjectId}/topics`)
        .then(response => response.json())
        .then(data => {
            topicsSelect.innerHTML = '<option value="">Tất cả chương/bài</option>';
            data.forEach(topic => {
                const option = document.createElement('option');
                option.value = topic.id;
                option.textContent = `${topic.name} (${topic.questions_count || 0} câu)`;
                topicsSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading topics:', error);
            topicsSelect.innerHTML = '<option value="">Lỗi khi tải chương/bài</option>';
        });
}

// Show error popup if exists
@if(session('error_popup'))
    window.addEventListener('DOMContentLoaded', function() {
        showErrorPopup(`{{ session('error_popup') }}`);
    });
@endif

// Custom error popup function
function showErrorPopup(message) {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    // Create popup
    const popup = document.createElement('div');
    popup.style.cssText = `
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease-out;
    `;
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    popup.innerHTML = `
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <svg style="width: 30px; height: 30px; color: #DC2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 10px;">Lỗi Số Lượng Câu Hỏi!</h3>
        </div>
        <div style="background: #F9FAFB; border-left: 4px solid #DC2626; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <pre style="white-space: pre-wrap; font-family: inherit; margin: 0; color: #374151; line-height: 1.6;">${message}</pre>
        </div>
        <button onclick="this.closest('[style*=fixed]').remove()" style="
            width: 100%;
            background: linear-gradient(to right, #DC2626, #B91C1C);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        " onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            Đã hiểu, để tôi sửa lại
        </button>
    `;
    
    overlay.appendChild(popup);
    document.body.appendChild(overlay);
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.remove();
        }
    });
}
</script>
@endsection
