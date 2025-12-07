@extends('layouts.app')

@section('title', 'Họp Online - MegaLearning')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Họp Online 🎥</h1>
                <p class="text-blue-100 text-lg">Tham gia các buổi học trực tuyến với giáo viên</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-6xl">
                    💻
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Sắp Diễn Ra</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['upcoming'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Hôm Nay</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today'] }}</p>
                </div>
                <div class="text-4xl">⏰</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Đã Tham Gia</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['attended'] }}</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>
    </div>

    <!-- Ongoing Meetings -->
    @if($ongoingCalls->isNotEmpty())
        <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <h2 class="text-2xl font-bold text-red-900">Đang Diễn Ra</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($ongoingCalls as $call)
                    <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-red-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $call->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ $call->description }}</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        {{ $call->classRoom->subject->name }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $call->host->name }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Bắt đầu {{ $call->started_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                        <a href="{{ route('student.video-calls.join', $call->id) }}" 
                           class="block w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 text-center shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Tham Gia Ngay
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Upcoming Meetings -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Cuộc Họp Sắp Tới
        </h2>

        @if($upcomingCalls->isEmpty())
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📅</div>
                <p class="text-gray-500 text-lg">Chưa có cuộc họp nào được lên lịch</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($upcomingCalls as $call)
                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:border-blue-300">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $call->title }}</h3>
                                    @if($call->scheduled_at->isToday())
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Hôm nay</span>
                                    @elseif($call->scheduled_at->isTomorrow())
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Ngày mai</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 mb-4">{{ $call->description }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        {{ $call->classRoom->subject->name }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $call->scheduled_at->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $call->scheduled_at->format('H:i') }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $call->host->name }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $call->classRoom->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-3 ml-6">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Bắt đầu sau</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $call->scheduled_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('student.video-calls.show', $call->id) }}" 
                                   class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-medium py-2 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Past Meetings -->
    @if($pastCalls->isNotEmpty())
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-8 h-8 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lịch Sử
            </h2>

            <div class="space-y-3">
                @foreach($pastCalls as $call)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $call->title }}</h3>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                    <span>{{ $call->classRoom->subject->name }}</span>
                                    <span>•</span>
                                    <span>{{ $call->ended_at->format('d/m/Y H:i') }}</span>
                                    <span>•</span>
                                    @if($call->attendance->isNotEmpty())
                                        <span class="text-green-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Đã tham gia
                                        </span>
                                    @else
                                        <span class="text-red-600">Vắng mặt</span>
                                    @endif
                                </div>
                            </div>
                            @if($call->recording_url)
                                <a href="{{ $call->recording_url }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Xem lại
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto refresh every 60 seconds to check for new meetings
    setInterval(function() {
        location.reload();
    }, 60000);
</script>
@endpush
@endsection
