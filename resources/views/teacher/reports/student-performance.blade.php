@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.reports.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.reports.class-performance', $classRoom->id) }}">{{ $classRoom->name }}</a></li>
                    <li class="breadcrumb-item active">{{ $student->name }}</li>
                </ol>
            </nav>
            <h2 class="mb-0">
                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Kết quả học tập của học sinh
            </h2>
            <p class="text-muted mb-0">{{ $student->name }} - {{ $classRoom->name }}</p>
        </div>
    </div>

    <!-- Student Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['submitted_count'] }}/{{ $stats['total_exams'] }}</h3>
                    <p>Bài thi đã nộp</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['average_score'], 2) }}</h3>
                    <p>Điểm trung bình</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['attendance_rate'] }}%</h3>
                    <p>Tỷ lệ tham gia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['rank'] ?? 'N/A' }}</h3>
                    <p>Xếp hạng lớp</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Scores Timeline -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Biểu đồ điểm số
            </h5>
        </div>
        <div class="card-body">
            <canvas id="scoreChart" height="80"></canvas>
        </div>
    </div>

    <!-- Detailed Exam Submissions -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Chi tiết bài thi
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bài thi</th>
                            <th>Ngày nộp</th>
                            <th class="text-center">Điểm</th>
                            <th class="text-center">Thời gian làm</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>
                                <strong>{{ $submission->exam->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $submission->exam->subject->name }}</small>
                            </td>
                            <td>
                                @if($submission->submitted_at)
                                    {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="badge bg-warning">Chưa nộp</span>
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
                                        {{ number_format($submission->score, 2) }}/10
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Chưa chấm</span>
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
                                    <span class="badge bg-success">Đã chấm</span>
                                @elseif($submission->submitted_at)
                                    <span class="badge bg-warning">Chờ chấm</span>
                                @else
                                    <span class="badge bg-secondary">Chưa làm</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($submission->submitted_at)
                                    <a href="{{ route('teacher.grading.show', $submission->id) }}" class="btn btn-sm btn-outline-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Xem
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p>Không có dữ liệu bài thi</p>
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
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    height: 100%;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #1f2937;
}

.stat-content p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('scoreChart').getContext('2d');
    
    const examTitles = @json($submissions->pluck('exam.title'));
    const scores = @json($submissions->map(function($s) { return $s->score ?? 0; }));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: examTitles,
            datasets: [{
                label: 'Điểm số',
                data: scores,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Điểm: ' + context.parsed.y.toFixed(2) + '/10';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    ticks: {
                        stepSize: 2,
                        callback: function(value) {
                            return value + '/10';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection
