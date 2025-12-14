@extends('admin.layout')

@section('title', 'Log Hoạt động')
@section('page-title', '📋 Log Hoạt động Chi tiết')
@section('page-description', 'Theo dõi tất cả hoạt động của người dùng trong hệ thống')

@push('styles')
<style>
    .log-card {
        background: white;
        border-radius: 0.75rem;
        border-left: 4px solid #e5e7eb;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    .log-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-left-color: #3b82f6;
    }
    .action-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .filter-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
</style>
@endpush

@section('content')

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Tổng hoạt động</p>
                <h3 class="text-3xl font-bold">{{ number_format($logs->total()) }}</h3>
            </div>
            <div class="text-4xl opacity-80">📊</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Người dùng hoạt động</p>
                <h3 class="text-3xl font-bold">{{ number_format($logs->pluck('user_id')->unique()->count()) }}</h3>
            </div>
            <div class="text-4xl opacity-80">👥</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Loại hành động</p>
                <h3 class="text-3xl font-bold">{{ $actionTypes->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">🎯</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm mb-1">Loại đối tượng</p>
                <h3 class="text-3xl font-bold">{{ $entityTypes->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">📦</div>
        </div>
    </div>
</div>

<!-- Advanced Filters -->
<div class="filter-card mb-6">
    <form action="{{ route('admin.statistics.activity-logs') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i data-feather="activity" class="w-4 h-4 inline text-blue-600"></i> Hành động
                </label>
                <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i data-feather="box" class="w-4 h-4 inline text-green-600"></i> Loại đối tượng
                </label>
                <select name="entity_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    @foreach($entityTypes as $type)
                        <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i data-feather="calendar" class="w-4 h-4 inline text-purple-600"></i> Từ ngày
                </label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i data-feather="calendar" class="w-4 h-4 inline text-purple-600"></i> Đến ngày
                </label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">
                    <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.statistics.activity-logs') }}" 
                   class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i data-feather="x" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Activity Logs Timeline -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-bold text-gray-800">
            <i data-feather="list" class="w-5 h-5 inline text-blue-600"></i>
            Timeline Hoạt động
        </h3>
    </div>

    <div class="p-6 space-y-3">
        @forelse($logs as $log)
        <div class="log-card">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                        {{ $log->user ? substr($log->user->name, 0, 1) : '?' }}
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900">
                                {{ $log->user->name ?? 'Unknown User' }}
                            </span>
                            <span class="action-badge 
                                @if(str_contains($log->action, 'create')) bg-green-100 text-green-800
                                @elseif(str_contains($log->action, 'update')) bg-blue-100 text-blue-800
                                @elseif(str_contains($log->action, 'delete')) bg-red-100 text-red-800
                                @elseif(str_contains($log->action, 'login')) bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $log->action }}
                            </span>
                            @if($log->entity_type)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ $log->entity_type }}
                            </span>
                            @endif
                        </div>
                        
                        @if($log->description)
                        <p class="text-sm text-gray-600 mb-2">{{ $log->description }}</p>
                        @endif
                        
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>
                                <i data-feather="clock" class="w-3 h-3 inline"></i>
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                            <span>
                                <i data-feather="calendar" class="w-3 h-3 inline"></i>
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </span>
                            @if($log->ip_address)
                            <span>
                                <i data-feather="globe" class="w-3 h-3 inline"></i>
                                {{ $log->ip_address }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có log hoạt động</h3>
            <p class="text-gray-600">Chưa có hoạt động nào được ghi nhận với bộ lọc hiện tại</p>
        </div>
        @endforelse
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
@endpush
