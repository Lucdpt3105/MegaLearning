@extends('admin.layout')

@section('title', 'Dashboard Thống kê')
@section('page-title', '📊 Dashboard Thống kê Tổng quan')
@section('page-description', 'Theo dõi thống kê và phân tích hoạt động hệ thống realtime')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .widget-container {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
    <!-- Dashboard chính -->
    
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng người dùng</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overviewStats['total_users']) }}</h3>
                    <p class="text-xs text-blue-600 mt-2">
                        <span class="font-semibold">{{ number_format($overviewStats['active_users_today']) }}</span> hoạt động hôm nay
                    </p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-blue-600">
                    👥
                </div>
            </div>
        </div>

        <!-- Teachers -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Giáo viên</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overviewStats['total_teachers']) }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        {{ number_format($overviewStats['total_subjects']) }} môn học
                    </p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-green-500 to-green-600">
                    👨‍🏫
                </div>
            </div>
        </div>

        <!-- Students -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Học sinh</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overviewStats['total_students']) }}</h3>
                    <p class="text-xs text-purple-600 mt-2">
                        {{ number_format($overviewStats['total_submissions']) }} bài nộp
                    </p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-purple-500 to-purple-600">
                    👨‍🎓
                </div>
            </div>
        </div>

        <!-- Exams -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Đề thi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overviewStats['total_exams']) }}</h3>
                    <p class="text-xs text-orange-600 mt-2">
                        {{ number_format($overviewStats['total_documents']) }} tài liệu
                    </p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-orange-500 to-orange-600">
                    📝
                </div>
            </div>
        </div>
    </div>

    <!-- Log đăng nhập (<<include>> - luôn hiển thị) -->
    <div class="widget-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                📊 Log Đăng nhập (24 giờ qua)
            </h3>
            <span class="text-sm text-gray-500">Tỷ lệ thành công: <strong class="text-green-600">{{ $loginStats['success_rate'] }}%</strong></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 mb-1">Đăng nhập thành công</p>
                        <p class="text-2xl font-bold text-green-800">{{ number_format($loginStats['successful']) }}</p>
                    </div>
                    <span class="text-3xl">✅</span>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-700 mb-1">Đăng nhập thất bại</p>
                        <p class="text-2xl font-bold text-red-800">{{ number_format($loginStats['failed']) }}</p>
                    </div>
                    <span class="text-3xl">❌</span>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 mb-1">Tổng lượt đăng nhập</p>
                        <p class="text-2xl font-bold text-blue-800">{{ number_format($loginStats['total']) }}</p>
                    </div>
                    <span class="text-3xl">🔐</span>
                </div>
            </div>
        </div>

        <!-- Login chart by hour -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Lượt đăng nhập theo giờ</h4>
            <div class="chart-container">
                <canvas id="loginHourlyChart"></canvas>
            </div>
        </div>

        <!-- Top users -->
        @if($loginStats['top_users']->count() > 0)
        <div class="mt-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Top người dùng đăng nhập nhiều nhất</h4>
            <div class="space-y-2">
                @foreach($loginStats['top_users']->take(5) as $index => $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $user->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-gray-700">{{ $user->login_count }} lần</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- UC-ADM-053, 054, 055: Extended widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Activity Logs Link -->
        <a href="{{ route('admin.statistics.activity-logs') }}" 
           class="widget-container hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-gradient-to-br from-indigo-500 to-indigo-600">
                    📋
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 group-hover:text-indigo-600 transition">Log Hoạt động</h4>
                    <p class="text-sm text-gray-500">Xem chi tiết hoạt động</p>
                </div>
            </div>
        </a>

        <!-- Usage Duration Link -->
        <a href="{{ route('admin.statistics.usage-duration') }}" 
           class="widget-container hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-gradient-to-br from-pink-500 to-pink-600">
                    ⏱️
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 group-hover:text-pink-600 transition">Thống kê Thời lượng</h4>
                    <p class="text-sm text-gray-500">Thời gian sử dụng trung bình</p>
                </div>
            </div>
        </a>

        <!-- Participation Link -->
        <a href="{{ route('admin.statistics.participation') }}" 
           class="widget-container hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-gradient-to-br from-teal-500 to-teal-600">
                    📈
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 group-hover:text-teal-600 transition">Số người tham gia</h4>
                    <p class="text-sm text-gray-500">Thống kê theo môn học</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Student Rankings (UC-SYS-004) -->
    <div class="widget-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                🏆 Xếp hạng Học sinh
            </h3>
            <a href="{{ route('admin.statistics.rankings') }}" 
               class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Xem tất cả →
            </a>
        </div>
        <p class="text-sm text-gray-600 mb-4">
            Dữ liệu xếp hạng được tự động tính toán bởi hệ thống (Batch Job)
        </p>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
            <p class="text-blue-700">
                Nhấn "Xem tất cả" để xem chi tiết bảng xếp hạng theo lớp và môn học
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Login hourly chart
    const hourlyData = @json($loginStats['hourly']);
    
    const hours = Array.from({length: 24}, (_, i) => i);
    const counts = hours.map(h => {
        const found = hourlyData.find(d => d.hour === h);
        return found ? found.count : 0;
    });

    const ctx = document.getElementById('loginHourlyChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: hours.map(h => h + ':00'),
                datasets: [{
                    label: 'Lượt đăng nhập',
                    data: counts,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
