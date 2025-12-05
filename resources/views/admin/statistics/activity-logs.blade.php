@extends('admin.layout')

@section('title', 'Log Hoạt động')
@section('page-title', 'Log Hoạt động Chi tiết')
@section('page-description', 'Xem chi tiết các hoạt động của người dùng trong hệ thống')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Filters -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hành động</label>
                    <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tất cả</option>
                        @foreach($actionTypes as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại thực thể</label>
                    <select name="entity_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tất cả</option>
                        @foreach($entityTypes as $type)
                            <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        🔍 Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Activity Logs Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Người dùng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thực thể
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Chi tiết
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $log->user->name }}</div>
                                        <div class="text-gray-500">{{ $log->user->email }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ str_contains($log->action, 'login') ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ str_contains($log->action, 'create') ? 'bg-green-100 text-green-800' : '' }}
                                    {{ str_contains($log->action, 'update') ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ str_contains($log->action, 'delete') ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !str_contains($log->action, 'login') && !str_contains($log->action, 'create') && !str_contains($log->action, 'update') && !str_contains($log->action, 'delete') ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->entity_type)
                                    <div>
                                        <span class="font-medium">{{ ucfirst($log->entity_type) }}</span>
                                        @if($log->entity_id)
                                            <span class="text-gray-400">#{{ $log->entity_id }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($log->description)
                                    <button onclick="showDetails({{ $log->id }})" 
                                            class="text-blue-600 hover:text-blue-800">
                                        Xem chi tiết
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Không có log nào được tìm thấy
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showDetails(logId) {
    alert('Chi tiết log #' + logId + ' sẽ được hiển thị trong modal');
    // TODO: Implement modal to show old_values and new_values
}
</script>
@endpush
