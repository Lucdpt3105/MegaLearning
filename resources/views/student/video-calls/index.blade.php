@extends('layouts.app')

@section('title', 'Họp Online - MegaLearning')

@section('content')
<div class="space-y-6">
    <!-- Modern Header with Image -->
    <div class="relative overflow-hidden rounded-2xl shadow-xl h-64">
        <img src="https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=1200&q=80" 
             alt="Video Conference" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-purple-900/85 to-pink-900/80"></div>
        <div class="relative h-full flex items-center justify-between p-8">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-bold text-white mb-3">Video Conferences</h1>
                <p class="text-indigo-100 text-lg">Join live sessions with your teachers and classmates</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid with Images -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?w=600&q=80" 
                 alt="Calendar" 
                 class="absolute inset-0 w-full h-full object-cover opacity-15">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-blue-600/5"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Upcoming Sessions</p>
                    <p class="text-4xl font-bold text-blue-600 mt-1">{{ $stats['upcoming'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80" 
                 alt="Today" 
                 class="absolute inset-0 w-full h-full object-cover opacity-15">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-green-600/5"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Today's Meetings</p>
                    <p class="text-4xl font-bold text-green-600 mt-1">{{ $stats['today'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&q=80" 
                 alt="Completed" 
                 class="absolute inset-0 w-full h-full object-cover opacity-15">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-purple-600/5"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Attended Sessions</p>
                    <p class="text-4xl font-bold text-purple-600 mt-1">{{ $stats['attended'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
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
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                Upcoming Sessions
            </h2>
            @if($upcomingCalls->isNotEmpty())
                <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full font-medium">
                    {{ $upcomingCalls->count() }} scheduled
                </span>
            @endif
        </div>

        @if($upcomingCalls->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Upcoming Sessions</h3>
                <p class="text-gray-500">Check back later for scheduled video conferences</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($upcomingCalls as $call)
                    <div class="group border-2 border-gray-100 hover:border-indigo-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center">
                            <!-- Left: Thumbnail -->
                            <div class="relative w-48 h-40 shrink-0 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&q=80" 
                                     alt="Meeting" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/80 to-purple-600/80"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <div class="text-3xl font-bold">{{ $call->scheduled_at->format('d') }}</div>
                                        <div class="text-sm font-medium">{{ $call->scheduled_at->format('M') }}</div>
                                    </div>
                                </div>
                                @if($call->scheduled_at->isToday())
                                    <div class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        Today
                                    </div>
                                @elseif($call->scheduled_at->isTomorrow())
                                    <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        Tomorrow
                                    </div>
                                @endif
                            </div>

                            <!-- Middle: Content -->
                            <div class="flex-1 p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $call->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $call->description }}</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="flex items-center gap-2 text-sm">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-700 font-medium truncate">{{ $call->classRoom->subject->name }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-sm">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-700 font-medium">{{ $call->scheduled_at->format('H:i') }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-sm">
                                        <img src="https://randomuser.me/api/portraits/{{ $call->host_id % 2 == 0 ? 'women' : 'men' }}/{{ $call->host_id }}.jpg" 
                                             alt="{{ $call->host->name }}" 
                                             class="w-8 h-8 rounded-lg object-cover ring-2 ring-gray-100">
                                        <span class="text-gray-700 font-medium truncate">{{ $call->host->name }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-sm">
                                        <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-700 font-medium truncate">{{ $call->classRoom->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Action -->
                            <div class="flex flex-col items-center justify-center gap-4 p-6 border-l border-gray-100 min-w-[180px]">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Starts in</p>
                                    <p class="text-lg font-bold text-indigo-600">{{ $call->scheduled_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('student.video-calls.show', $call->id) }}" 
                                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-center text-sm">
                                    View Details
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
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Session History
                </h2>
                <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full font-medium">
                    {{ $pastCalls->count() }} sessions
                </span>
            </div>

            <div class="space-y-3">
                @foreach($pastCalls as $call)
                    <div class="group border border-gray-200 hover:border-gray-300 rounded-xl p-5 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <!-- Thumbnail -->
                            <div class="relative w-20 h-20 shrink-0 rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?w=200&q=80" 
                                     alt="Call" 
                                     class="w-full h-full object-cover opacity-60">
                                <div class="absolute inset-0 bg-gray-900/40 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $call->title }}</h3>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        {{ $call->classRoom->subject->name }}
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $call->ended_at->format('d/m/Y H:i') }}
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    @if($call->attendance->isNotEmpty())
                                        <span class="flex items-center gap-1 text-green-600 font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Attended
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 text-red-600 font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Absent
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Recording -->
                            @if($call->recording_url)
                                <a href="{{ $call->recording_url }}" target="_blank" 
                                   class="group/btn flex items-center gap-2 px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-xl transition-all duration-200">
                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm">Watch Recording</span>
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
