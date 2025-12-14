@extends('admin.layout')

@section('title', 'Thống kê Thời lượng')
@section('page-title', '⏱️ Thống kê Thời lượng Sử dụng')
@section('page-description', 'Phân tích thời gian sử dụng hệ thống theo vai trò và người dùng')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .chart-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }
</style>
@endpush

@section('content')

<!-- Period Selector -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.statistics.usage-duration') }}" method="GET" class="flex items-center gap-4">
        <label class="text-sm font-medium text-gray-700">
            <i data-feather="calendar" class="w-4 h-4 inline text-blue-600"></i> Chọn khoảng thời gian:
        </label>
        <div class="flex gap-2">
            <button type="submit" name="period" value="7days" 
                    class="px-4 py-2 rounded-lg transition {{ $period == '7days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                7 ngày qua
            </button>
            <button type="submit" name="period" value="30days" 
                    class="px-4 py-2 rounded-lg transition {{ $period == '30days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                30 ngày qua
            </button>
            <button type="submit" name="period" value="90days" 
                    class="px-4 py-2 rounded-lg transition {{ $period == '90days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                90 ngày qua
            </button>
        </div>
    </form>
</div>

<!-- Usage by Role -->
<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i data-feather="users" class="w-5 h-5 inline text-purple-600"></i>
        Thời gian sử dụng theo vai trò
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($usageByRole as $role)
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 text-lg">{{ ucfirst($role->role_name) }}</h4>
                <span class="text-3xl">
                    @if($role->role_name == 'admin') 👑
                    @elseif($role->role_name == 'teacher') 👨‍🏫
                    @else 👨‍🎓
                    @endif
                </span>
            </div>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Số người dùng</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($role->user_count) }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Tổng hành động</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($role->total_actions) }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">TB hành động/người</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($role->avg_actions_per_user, 1) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Daily Usage Chart -->
<div class="chart-card mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i data-feather="trending-up" class="w-5 h-5 inline text-green-600"></i>
        Hoạt động theo ngày
    </h3>
    <div style="position: relative; height: 350px;">
        <canvas id="dailyUsageChart"></canvas>
    </div>
</div>

<!-- Top Active Users -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
        <h3 class="text-lg font-bold text-gray-800">
            <i data-feather="zap" class="w-5 h-5 inline text-yellow-600"></i>
            Top 20 người dùng hoạt động nhiều nhất
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Hạng</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Người dùng</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Số hành động</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Mức độ hoạt động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($topActiveUsers as $index => $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-white
                            {{ $index < 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 'bg-gray-400' }}">
                            {{ $index + 1 }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center font-bold">
                                {{ substr($user->user->name ?? 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $user->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-2xl font-bold text-blue-600">{{ number_format($user->action_count) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-full max-w-xs bg-gray-200 rounded-full h-3">
                                @php
                                    $maxActions = $topActiveUsers->first()->action_count ?? 1;
                                    $percentage = ($user->action_count / $maxActions) * 100;
                                @endphp
                                <div class="h-3 rounded-full transition-all duration-500
                                    {{ $percentage >= 80 ? 'bg-gradient-to-r from-green-500 to-green-600' : 
                                       ($percentage >= 50 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 
                                        'bg-gradient-to-r from-gray-400 to-gray-500') }}"
                                    style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ number_format($percentage, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
    
    // Daily Usage Chart
    const dailyData = @json($dailyUsage);
    
    const ctx = document.getElementById('dailyUsageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => new Date(d.date).toLocaleDateString('vi-VN')),
                datasets: [
                    {
                        label: 'Tổng hành động',
                        data: dailyData.map(d => d.total_actions),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Người dùng hoạt động',
                        data: dailyData.map(d => d.active_users),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Số hành động'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Số người dùng'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
