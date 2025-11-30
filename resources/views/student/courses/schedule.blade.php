@extends('layouts.app')

@section('title', 'Lịch Học - ' . $classRoom->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('student.courses.index') }}" class="hover:text-indigo-600">Khóa Học Của Tôi</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li><a href="{{ route('student.courses.show', $classRoom->id) }}" class="hover:text-indigo-600">{{ $classRoom->name }}</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li class="text-gray-900 font-semibold">Lịch Học</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Lịch Học</h1>
        <p class="text-gray-600">{{ $classRoom->name }} - {{ $classRoom->subject->name }}</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('student.courses.show', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tổng Quan
            </a>
            <a href="{{ route('student.courses.materials', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tài Liệu
            </a>
            <a href="{{ route('student.courses.schedule', $classRoom->id) }}" class="border-b-2 border-indigo-500 py-4 px-1 text-sm font-medium text-indigo-600">
                Lịch Học
            </a>
        </nav>
    </div>

    <!-- Video Calls Schedule -->
    @if($videoCalls->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có lịch học</h3>
            <p class="text-gray-600">Giảng viên chưa tạo lịch học cho khóa học này.</p>
        </div>
    @else
        <!-- Group by status -->
        @php
            $upcoming = $videoCalls->filter(function($call) {
                return $call->status === 'scheduled' && $call->scheduled_at > now();
            });
            $past = $videoCalls->filter(function($call) {
                return $call->status === 'completed' || ($call->status === 'scheduled' && $call->scheduled_at <= now());
            });
            $cancelled = $videoCalls->where('status', 'cancelled');
        @endphp

        <!-- Upcoming Sessions -->
        @if($upcoming->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buổi Học Sắp Tới
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($upcoming as $call)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border-l-4 border-green-500">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $call->title }}</h3>
                                        @if($call->description)
                                            <p class="text-sm text-gray-600 mb-3">{{ $call->description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Sắp diễn ra</span>
                                </div>

                                <!-- Time Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $call->scheduled_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $call->scheduled_at->format('H:i') }}</span>
                                        @if($call->duration)
                                            <span class="ml-2 text-gray-500">({{ $call->duration }} phút)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Countdown or Join Button -->
                                @php
                                    $diffInMinutes = now()->diffInMinutes($call->scheduled_at, false);
                                @endphp

                                @if($diffInMinutes > 0 && $diffInMinutes <= 15)
                                    <a href="{{ route('teacher.video-calls.join', $call->id) }}" 
                                       class="block w-full text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Tham Gia Ngay
                                    </a>
                                @else
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Bắt đầu sau:</p>
                                        <p class="text-2xl font-bold text-indigo-600">
                                            @if($diffInMinutes < 60)
                                                {{ $diffInMinutes }} phút
                                            @elseif($diffInMinutes < 1440)
                                                {{ floor($diffInMinutes / 60) }} giờ
                                            @else
                                                {{ floor($diffInMinutes / 1440) }} ngày
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Past Sessions -->
        @if($past->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buổi Học Đã Qua
                </h2>
                <div class="space-y-4">
                    @foreach($past as $call)
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-300">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $call->title }}</h3>
                                    @if($call->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $call->description }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $call->scheduled_at->format('d/m/Y H:i') }}
                                        </span>
                                        @if($call->duration)
                                            <span>{{ $call->duration }} phút</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Đã kết thúc</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Cancelled Sessions -->
        @if($cancelled->isNotEmpty())
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Buổi Học Đã Hủy
                </h2>
                <div class="space-y-4">
                    @foreach($cancelled as $call)
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-300 opacity-75">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 line-through">{{ $call->title }}</h3>
                                    <div class="text-sm text-gray-600">
                                        <span>{{ $call->scheduled_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Đã hủy</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
