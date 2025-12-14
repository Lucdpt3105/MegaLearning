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
        <form action="{{ route('teacher.video-calls.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Basic Information -->
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Thông tin buổi học</h2>
                    
                    <div class="space-y-4">
                        <!-- Title & Class (2 columns) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tiêu đề <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" required
                                       value="{{ old('title') }}"
                                       class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="VD: Ôn tập Chương 1 - Hàm số">
                                @error('title')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Lớp học <span class="text-red-500">*</span>
                                </label>
                                <select name="class_room_id" id="class_room_id" required
                                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Chọn lớp học</option>
                                    @foreach($classRooms as $classRoom)
                                        <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                            {{ $classRoom->name }} - {{ $classRoom->subject ? $classRoom->subject->name : 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_room_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mô tả
                            </label>
                            <textarea name="description" id="description" rows="2"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                      placeholder="Nội dung, mục tiêu của buổi học...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Lịch trình</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Thời gian bắt đầu <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                                   value="{{ old('scheduled_at', now()->addHour()->format('Y-m-d\TH:i')) }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('scheduled_at')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Thời lượng (phút) <span class="text-red-500">*</span>
                            </label>
                            <select name="duration" id="duration"
                                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 phút</option>
                                <option value="45" {{ old('duration') == 45 ? 'selected' : '' }}>45 phút</option>
                                <option value="60" {{ old('duration', 60) == 60 ? 'selected' : '' }}>60 phút</option>
                                <option value="90" {{ old('duration') == 90 ? 'selected' : '' }}>90 phút</option>
                                <option value="120" {{ old('duration') == 120 ? 'selected' : '' }}>120 phút</option>
                            </select>
                            @error('duration')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Platform & Settings Combined -->
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Cài đặt nền tảng</h2>
                    
                    <div class="space-y-4">
                        <!-- Zoom Platform (Compact) -->
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md bg-gray-50">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="platform" value="zoom" checked
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">Zoom</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">Chất lượng cao • Ghi hình tự động • Báo cáo chi tiết</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recording Checkbox (Inline) -->
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="is_recording" value="1" 
                                   {{ old('is_recording') ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 rounded focus:ring-red-500">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">🔴 Ghi hình buổi học</div>
                                <div class="text-xs text-gray-600">Video sẽ được lưu lại sau khi kết thúc để học sinh xem lại</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Info Box (Compact) -->
                <div class="p-5 bg-blue-50">
                    <div class="flex gap-3">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs text-blue-900 space-y-1">
                            <p class="font-semibold">Lưu ý:</p>
                            <p>• Hệ thống tự động tạo phòng Zoom và link tham gia</p>
                            <p>• Học sinh chỉ có thể tham gia trong thời gian đã lên lịch</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions (Right-aligned) -->
                <div class="p-5 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('teacher.video-calls.index') }}" 
                           class="px-4 py-2 h-10 inline-flex items-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            Hủy bỏ
                        </a>
                        <button type="submit" 
                                class="px-5 py-2 h-10 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors inline-flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Tạo buổi học
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
