@extends('layouts.app')

@section('title', $videoCall->title . ' - Họp Online')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('student.video-calls.index') }}" class="hover:text-blue-600">Họp Online</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li class="text-gray-900 font-semibold">{{ $videoCall->title }}</li>
        </ol>
    </nav>

    <!-- Meeting Header -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-3">{{ $videoCall->title }}</h1>
                <p class="text-blue-100 text-lg mb-4">{{ $videoCall->description }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>{{ $videoCall->classRoom && $videoCall->classRoom->subject ? $videoCall->classRoom->subject->name : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>{{ $videoCall->classRoom ? $videoCall->classRoom->name : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $videoCall->host ? $videoCall->host->name : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($videoCall->status === 'scheduled') bg-blue-500
                            @elseif($videoCall->status === 'in_progress') bg-green-500
                            @elseif($videoCall->status === 'ended') bg-gray-500
                            @else bg-red-500
                            @endif
                        ">
                            @if($videoCall->status === 'scheduled') Sắp diễn ra
                            @elseif($videoCall->status === 'in_progress') Đang diễn ra
                            @elseif($videoCall->status === 'ended') Đã kết thúc
                            @else Đã hủy
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            @if($videoCall->status === 'in_progress')
                <div class="ml-6">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Meeting Details -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Thông Tin Cuộc Họp</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Ngày họp</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $videoCall->scheduled_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Thời gian</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $videoCall->scheduled_at->format('H:i') }}</p>
                    </div>
                </div>

                @if($videoCall->started_at)
                <div class="flex items-start space-x-3">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Bắt đầu lúc</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $videoCall->started_at->format('H:i d/m/Y') }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="bg-orange-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Mã phòng</p>
                        <p class="text-lg font-semibold text-gray-900 font-mono">{{ $videoCall->room_code }}</p>
                    </div>
                </div>

                @if($attendance)
                <div class="flex items-start space-x-3">
                    <div class="bg-cyan-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Trạng thái tham gia</p>
                        <p class="text-lg font-semibold text-green-600">
                            @if($attendance->checked_in_at)
                                Đã tham gia lúc {{ $attendance->checked_in_at->format('H:i') }}
                            @else
                                Đã đăng ký
                            @endif
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Join Button -->
        @if($videoCall->status === 'in_progress' || ($videoCall->status === 'scheduled' && $videoCall->scheduled_at->lte(now())))
            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('student.video-calls.join', $videoCall->id) }}" 
                   class="block w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 text-center text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Tham Gia Cuộc Họp
                </a>
            </div>
        @elseif($videoCall->status === 'scheduled')
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-blue-800 font-medium">
                        Cuộc họp sẽ bắt đầu {{ $videoCall->scheduled_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @elseif($videoCall->status === 'ended')
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-gray-700 font-medium mb-3">Cuộc họp đã kết thúc</p>
                    @if($videoCall->recording_url)
                        <a href="{{ $videoCall->recording_url }}" target="_blank" 
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Xem lại buổi học
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="text-center">
        <a href="{{ route('student.video-calls.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách
        </a>
    </div>
</div>
@endsection
