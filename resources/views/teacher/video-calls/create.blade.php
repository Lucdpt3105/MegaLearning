@extends('layouts.app')

@section('title', 'Tạo Buổi học Trực tuyến')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('teacher.video-calls.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tạo Buổi học Trực tuyến 🎥</h1>
                    <p class="text-gray-600 mt-1">Tạo phòng học trực tuyến</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('teacher.video-calls.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông tin buổi học</h2>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                               value="{{ old('title') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="VD: Ôn tập Chương 1 - Hàm số">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Room -->
                    <div>
                        <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Lớp học <span class="text-red-500">*</span>
                        </label>
                        <select name="class_room_id" id="class_room_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Chọn lớp học</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }} - {{ $classRoom->subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Mô tả
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Nội dung, mục tiêu của buổi học...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Lịch trình</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian bắt đầu <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                               value="{{ old('scheduled_at', now()->addHour()->format('Y-m-d\TH:i')) }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời lượng dự kiến (phút) <span class="text-red-500">*</span>
                        </label>
                        <select name="duration" id="duration"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 phút</option>
                            <option value="45" {{ old('duration') == 45 ? 'selected' : '' }}>45 phút</option>
                            <option value="60" {{ old('duration', 60) == 60 ? 'selected' : '' }}>60 phút</option>
                            <option value="90" {{ old('duration') == 90 ? 'selected' : '' }}>90 phút</option>
                            <option value="120" {{ old('duration') == 120 ? 'selected' : '' }}>120 phút</option>
                        </select>
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Platform Selection -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Nền tảng họp video</h2>
                
                <div class="space-y-3">
                    <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="platform" value="jitsi" 
                               {{ old('platform', 'jitsi') === 'jitsi' ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">Jitsi Meet</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">FREE</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                ✅ Miễn phí hoàn toàn • ✅ Không giới hạn thời gian • ✅ Không cần tài khoản
                            </p>
                        </div>
                    </label>

                    <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="platform" value="zoom" 
                               {{ old('platform') === 'zoom' ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">Zoom</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">PREMIUM</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                ⭐ Chất lượng cao • 🎥 Ghi hình tự động • 📊 Báo cáo chi tiết
                            </p>
                        </div>
                    </label>
                </div>
                @error('platform')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recording Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Cài đặt ghi hình</h2>
                
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_recording" value="1" 
                           {{ old('is_recording') ? 'checked' : '' }}
                           class="w-5 h-5 mt-1 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                    <div>
                        <div class="font-medium text-gray-900">🔴 Ghi hình buổi học</div>
                        <div class="text-sm text-gray-500">Video sẽ được lưu lại sau khi kết thúc buổi học. Học sinh có thể xem lại sau này.</div>
                    </div>
                </label>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3 text-sm text-blue-800">
                        <p class="font-medium mb-1">Lưu ý:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Hệ thống sẽ tự động tạo mã phòng và link tham gia</li>
                            <li>Bạn có thể mời học sinh qua email hoặc chia sẻ mã phòng</li>
                            <li>Học sinh chỉ có thể tham gia trong thời gian đã lên lịch</li>
                            <li>Sử dụng Jitsi Meet - nền tảng video call miễn phí, bảo mật</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-6">
                <a href="{{ route('teacher.video-calls.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Tạo buổi học
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
