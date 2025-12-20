@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.reports.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active">Phân tích bài thi</li>
                </ol>
            </nav>
            <h2 class="mb-0">
                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Phân tích bài thi
            </h2>
            <p class="text-muted mb-0">{{ $exam->title }} - {{ $exam->subject ? $exam->subject->name : 'N/A' }}</p>
        </div>
        <a href="{{ route('teacher.exams.edit', $exam->id) }}" class="btn btn-outline-primary">
            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Chỉnh sửa bài thi
        </a>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_students'] }}</h3>
                    <p>Tổng học sinh</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['submitted_count'] }}</h3>
                    <p>Đã nộp bài</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['average_score'], 2) }}</h3>
                    <p>Điểm TB</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['highest_score'], 2) }}</h3>
                    <p>Điểm cao nhất</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['lowest_score'], 2) }}</h3>
                    <p>Điểm thấp nhất</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pass_rate'] }}%</h3>
                    <p>Tỷ lệ đậu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Distribution Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Phân bố điểm</h5>
                </div>
                <div class="card-body">
                    <canvas id="scoreDistributionChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Xếp loại</h5>
                </div>
                <div class="card-body">
                    <canvas id="gradePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Submissions Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Danh sách bài làm</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Học sinh</th>
                            <th>Lớp</th>
                            <th class="text-center">Ngày nộp</th>
                            <th class="text-center">Thời gian làm</th>
                            <th class="text-center">Điểm</th>
                            <th class="text-center">Xếp loại</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $index => $submission)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ strtoupper(substr($submission->student->name, 0, 2)) }}
                                    </div>
                                    {{ $submission->student->name }}
                                </div>
                            </td>
                            <td>{{ $submission->student->classRooms->first()->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($submission->submitted_at)
                                    {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="badge bg-warning">Chưa nộp</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->submitted_at && $submission->started_at)
                                    {{ gmdate('H:i:s', $submission->submitted_at->diffInSeconds($submission->started_at)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->score !== null)
                                    <span class="badge 
                                        @if($submission->score >= 8) bg-success
                                        @elseif($submission->score >= 6.5) bg-info
                                        @elseif($submission->score >= 5) bg-warning
                                        @else bg-danger
                                        @endif
                                    ">
                                        {{ number_format($submission->score, 2) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Chưa chấm</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->score !== null)
                                    @if($submission->score >= 8)
                                        <span class="badge bg-success">Giỏi</span>
                                    @elseif($submission->score >= 6.5)
                                        <span class="badge bg-info">Khá</span>
                                    @elseif($submission->score >= 5)
                                        <span class="badge bg-warning">Trung bình</span>
                                    @else
                                        <span class="badge bg-danger">Yếu</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->submitted_at)
                                    <a href="{{ route('teacher.grading.show', $submission->id) }}" class="btn btn-sm btn-outline-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Xem chi tiết
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Chưa có bài nộp nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 12px;
    height: 100%;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.stat-content h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    color: #1f2937;
}

.stat-content p {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const distCtx = document.getElementById('scoreDistributionChart').getContext('2d');
    const distribution = @json($stats['score_distribution']);
    
    new Chart(distCtx, {
        type: 'bar',
        data: {
            labels: ['0-2', '2-4', '4-5', '5-6.5', '6.5-8', '8-10'],
            datasets: [{
                label: 'Số học sinh',
                data: [
                    distribution['0-2'] ?? 0,
                    distribution['2-4'] ?? 0,
                    distribution['4-5'] ?? 0,
                    distribution['5-6.5'] ?? 0,
                    distribution['6.5-8'] ?? 0,
                    distribution['8-10'] ?? 0
                ],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
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

    // Grade Pie Chart
    const pieCtx = document.getElementById('gradePieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Giỏi', 'Khá', 'Trung bình', 'Yếu'],
            datasets: [{
                data: [
                    (distribution['8-10'] ?? 0),
                    (distribution['6.5-8'] ?? 0),
                    (distribution['5-6.5'] ?? 0),
                    ((distribution['0-2'] ?? 0) + (distribution['2-4'] ?? 0) + (distribution['4-5'] ?? 0))
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
