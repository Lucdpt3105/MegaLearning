@extends('layouts.app')

@section('title', 'Thông Báo')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Thông Báo</h1>
                    <p class="text-gray-600 mt-2">Tất cả thông báo của bạn</p>
                </div>
                @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                        Đánh dấu tất cả đã đọc
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @foreach($notifications as $notification)
            <div class="border-b border-gray-100 last:border-b-0 {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }}">
                <a href="{{ $notification->data['url'] ?? '#' }}" 
                   onclick="event.preventDefault(); markAsReadAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')"
                   class="block p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                            {{ $notification->type === 'exam_reminder' ? 'bg-indigo-100 text-indigo-600' : '' }}
                            {{ $notification->type === 'exam_update' ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $notification->type === 'general' ? 'bg-emerald-100 text-emerald-600' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-base font-bold text-gray-900 mb-1">{{ $notification->data['title'] ?? 'Thông báo' }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $notification->data['message'] ?? '' }}</p>
                                    
                                    @if(isset($notification->data['exam_title']))
                                    <div class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-xs text-gray-700 mb-2">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        {{ $notification->data['exam_title'] }}
                                    </div>
                                    @endif

                                    <div class="flex items-center space-x-3 text-xs text-gray-500">
                                        @if(isset($notification->data['teacher_name']))
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $notification->data['teacher_name'] }}
                                        </span>
                                        @endif
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                @if(!$notification->read_at)
                                <div class="w-3 h-3 bg-indigo-600 rounded-full flex-shrink-0"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl mb-6">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có thông báo nào</h3>
            <p class="text-gray-500">Bạn sẽ nhận được thông báo ở đây khi có cập nhật mới</p>
        </div>
        @endif
    </div>
</div>

<script>
function markAsReadAndRedirect(notificationId, url) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(() => {
        if (url && url !== '#') {
            window.location.href = url;
        }
    });
}
</script>
@endsection
