@extends('admin.layout')

@section('title', 'Log Hoạt động')
@section('page-title', 'Log Hoạt động')
@section('page-description', 'Theo dõi các hoạt động của người dùng trong hệ thống')

@push('styles')
<style>
    .log-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .log-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    .log-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .action-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Tổng logs</p>
                <h3 class="text-3xl font-bold">{{ number_format($logs->total()) }}</h3>
            </div>
            <div class="text-4xl opacity-80">📝</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Người dùng hoạt động</p>
                <h3 class="text-3xl font-bold">{{ $logs->unique('user_id')->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">👥</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Loại hoạt động</p>
                <h3 class="text-3xl font-bold">{{ $actionTypes->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">🎯</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm mb-1">Hôm nay</p>
                <h3 class="text-3xl font-bold">{{ $logs->where('created_at', '>=', today())->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">📅</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <form action="{{ route('admin.statistics.activity-logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại hoạt động</label>
            <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                @foreach($actionTypes as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại đối tượng</label>
            <select name="entity_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                @foreach($entityTypes as $type)
                    <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.statistics.activity-logs') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>

<!-- Activity Logs Timeline -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i data-feather="activity" class="w-5 h-5 inline text-blue-600"></i>
        Timeline Hoạt động
    </h3>

    @forelse($logs as $log)
        <div class="log-card">
            <div class="flex items-start gap-4">
                <div class="log-icon {{ $log->action == 'login' ? 'bg-green-100 text-green-600' : ($log->action == 'create' ? 'bg-blue-100 text-blue-600' : ($log->action == 'update' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600')) }}">
                    @if($log->action == 'login')
                        🔐
                    @elseif($log->action == 'create')
                        ➕
                    @elseif($log->action == 'update')
                        ✏️
                    @elseif($log->action == 'delete')
                        🗑️
                    @else
                        📋
                    @endif
                </div>

                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="action-badge {{ $log->action == 'login' ? 'bg-green-100 text-green-700' : ($log->action == 'create' ? 'bg-blue-100 text-blue-700' : ($log->action == 'update' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                            @if($log->entity_type)
                                <span class="text-gray-600 text-sm ml-2">{{ $log->entity_type }}</span>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="text-gray-700 mb-1">
                        <strong>{{ $log->user ? $log->user->name : 'System' }}</strong>
                        <span class="text-gray-600">{{ $log->user ? "({$log->user->email})" : '' }}</span>
                    </div>

                    @if($log->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>
                    @endif

                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                        <span><i data-feather="calendar" class="w-3 h-3 inline"></i> {{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                        @if($log->ip_address)
                            <span><i data-feather="globe" class="w-3 h-3 inline"></i> {{ $log->ip_address }}</span>
                        @endif
                        @if($log->entity_id)
                            <span><i data-feather="hash" class="w-3 h-3 inline"></i> ID: {{ $log->entity_id }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-gray-500">
            <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
            <p class="text-lg">Không có log hoạt động nào</p>
            <p class="text-sm">Thử điều chỉnh bộ lọc để xem kết quả khác</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Initialize Feather icons
    feather.replace();
</script>
@endpush
