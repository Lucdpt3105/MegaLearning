@extends('admin.layout')

@section('title', 'Thống kê Học sinh')
@section('page-title', 'Thống kê Học sinh')
@section('page-description', 'Phân tích chi tiết kết quả học tập và hoạt động của học sinh')

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
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .performance-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')

<!-- Overall Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Tổng học sinh</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overallStats['total_students']) }}</h3>
                <p class="text-xs text-green-600 mt-2">
                    {{ number_format($overallStats['active_students']) }} hoạt động
                </p>
            </div>
            <div class="stat-icon bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                👨‍🎓
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Điểm TB toàn hệ thống</p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $overallStats['average_score_all'] ? number_format($overallStats['average_score_all'], 2) : 'N/A' }}
                </h3>
                <p class="text-xs text-gray-500 mt-2">/10 điểm</p>
            </div>
            <div class="stat-icon bg-gradient-to-br from-green-500 to-green-600 text-white">
                📊
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">TB bài nộp/học sinh</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($overallStats['average_submissions'], 1) }}</h3>
                <p class="text-xs text-gray-500 mt-2">bài thi</p>
            </div>
            <div class="stat-icon bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                📝
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Top performers</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $topPerformers->count() }}</h3>
                <p class="text-xs text-yellow-600 mt-2">học sinh xuất sắc</p>
            </div>
            <div class="stat-icon bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
                🏆
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.statistics.students') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
            <select name="class_room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tất cả lớp</option>
                @foreach($classRooms as $class)
                    <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} - {{ $class->subject->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
            <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tất cả môn</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Tên hoặc email học sinh..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.statistics.students') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Score Distribution Chart -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i data-feather="bar-chart-2" class="w-5 h-5 inline text-blue-600"></i>
            Phân bố điểm số
        </h3>
        <div class="chart-container">
            <canvas id="scoreDistributionChart"></canvas>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i data-feather="award" class="w-5 h-5 inline text-yellow-600"></i>
            Top 10 học sinh xuất sắc
        </h3>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @forelse($topPerformers as $index => $performer)
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 text-white flex items-center justify-center font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $performer->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $performer->email }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">{{ number_format($performer->avg_score, 2) }}</p>
                    <p class="text-xs text-gray-500">điểm TB</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">
            <i data-feather="users" class="w-5 h-5 inline text-blue-600"></i>
            Danh sách học sinh ({{ $students->total() }})
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STT</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Học sinh</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Số lớp</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Bài nộp</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Đã chấm</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Điểm TB</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cao nhất</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Thấp nhất</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Hoàn thành</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($students as $index => $student)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $student->enrolled_classes }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-900 font-medium">
                        {{ $student->total_submissions }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-green-600">{{ $student->graded_submissions }}</span>
                        @if($student->pending_submissions > 0)
                        <span class="text-xs text-gray-500">/ +{{ $student->pending_submissions }} chờ</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($student->average_score !== null)
                        <span class="text-lg font-bold 
                            @if($student->average_score >= 8) text-green-600
                            @elseif($student->average_score >= 6.5) text-blue-600
                            @elseif($student->average_score >= 5) text-yellow-600
                            @else text-red-600
                            @endif">
                            {{ number_format($student->average_score, 2) }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($student->highest_score !== null)
                        <span class="text-sm font-medium text-green-600">{{ number_format($student->highest_score, 2) }}</span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($student->lowest_score !== null)
                        <span class="text-sm font-medium text-red-600">{{ number_format($student->lowest_score, 2) }}</span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300
                                    @if($student->completion_rate >= 80) bg-green-500
                                    @elseif($student->completion_rate >= 50) bg-blue-500
                                    @else bg-yellow-500
                                    @endif"
                                    style="width: {{ $student->completion_rate }}%">
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-600">{{ $student->completion_rate }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <i data-feather="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy học sinh</h3>
                        <p class="text-gray-600">Thử thay đổi bộ lọc hoặc tìm kiếm khác</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $students->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreDistributionChart');
    if (scoreCtx) {
        const scoreData = @json($scoreDistribution);
        
        new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: scoreData.map(item => item.score_range),
                datasets: [{
                    label: 'Số lượng học sinh',
                    data: scoreData.map(item => item.count),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // 9-10
                        'rgba(59, 130, 246, 0.8)',  // 8-9
                        'rgba(147, 51, 234, 0.8)',  // 7-8
                        'rgba(251, 191, 36, 0.8)',  // 6-7
                        'rgba(249, 115, 22, 0.8)',  // 5-6
                        'rgba(239, 68, 68, 0.8)',   // 0-5
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(147, 51, 234)',
                        'rgb(251, 191, 36)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
