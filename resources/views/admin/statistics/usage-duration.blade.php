@extends('admin.layout')

@section('title', 'Thống kê Thời lượng')
@section('page-title', 'Thống kê Thời lượng Sử dụng')
@section('page-description', 'Phân tích mức độ tương tác và thời gian sử dụng hệ thống')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 350px;
    }
</style>
@endpush

@section('content')
    <!-- Period Selector -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Chọn khoảng thời gian</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '7days']) }}" 
                       class="px-4 py-2 rounded-lg {{ $period == '7days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        7 ngày
                    </a>
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '30days']) }}" 
                       class="px-4 py-2 rounded-lg {{ $period == '30days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        30 ngày
                    </a>
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '90days']) }}" 
                       class="px-4 py-2 rounded-lg {{ $period == '90days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        90 ngày
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage by Role -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">📊 Thống kê theo Vai trò</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($usageByRole as $role)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xl font-bold text-gray-800">{{ ucfirst($role->role_name) }}</h4>
                        <span class="text-3xl">
                            @if($role->role_name == 'admin') 👨‍💼
                            @elseif($role->role_name == 'teacher') 👨‍🏫
                            @else 👨‍🎓
                            @endif
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Số người dùng:</span>
                            <span class="text-lg font-bold text-gray-800">{{ number_format($role->user_count) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tổng hoạt động:</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($role->total_actions) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Trung bình/người:</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($role->avg_actions_per_user, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Usage Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Hoạt động theo Ngày</h3>
        <div class="chart-container">
            <canvas id="dailyUsageChart"></canvas>
        </div>
    </div>

    <!-- Top Active Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">🏆 Top Người dùng Hoạt động Nhiều Nhất</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hạng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số hoạt động</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mức độ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topActiveUsers as $index => $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full 
                                    {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $index == 1 ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $index == 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $index > 2 ? 'bg-blue-100 text-blue-700' : '' }}
                                    font-bold">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-900">{{ $user->user->name ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->user->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-blue-600">{{ number_format($user->action_count) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->action_count > 1000)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        🔥 Cực kỳ tích cực
                                    </span>
                                @elseif($user->action_count > 500)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✨ Rất tích cực
                                    </span>
                                @elseif($user->action_count > 100)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        👍 Tích cực
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        📊 Bình thường
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyUsage);
    
    new Chart(document.getElementById('dailyUsageChart'), {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [
                {
                    label: 'Tổng hoạt động',
                    data: dailyData.map(d => d.total_actions),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Người dùng hoạt động',
                    data: dailyData.map(d => d.active_users),
                    type: 'line',
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Số hoạt động'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Người dùng'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            }
        }
    });
});
</script>
@endpush
