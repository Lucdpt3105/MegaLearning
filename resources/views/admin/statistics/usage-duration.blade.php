@extends('admin.layout')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-description', 'Your teaching hub for managing courses and tracking student progress')

@section('content')
    {{-- Header chào giáo viên --}}
    <div class="main-wrapper-page-title d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="mb-1">Teacher Dashboard</h2>
            <p class="mb-0 text-muted">
                Welcome back! Here's what's happening with your classes today.
            </p>
        </div>
        <div class="d-flex align-items-center justify-content-center"
             style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);color:#fff;font-size:26px;box-shadow:0 10px 25px rgba(79,70,229,.4);">
            👨‍🏫
        </div>
    </div>

    {{-- Ô tìm kiếm --}}
    <div class="mb-4">
        <div class="d-flex align-items-center px-3 py-2"
             style="background:#fff;border-radius:999px;border:2px solid #e0e7ff;box-shadow:0 4px 15px rgba(148,163,184,.12);">
            <i data-feather="search" class="me-2" style="width:18px;height:18px;color:#9ca3af;"></i>
            <input type="text"
                   class="form-control border-0 shadow-none"
                   style="background:transparent;font-size:14px;"
                   placeholder="Search for students, courses, or topics...">
        </div>
    </div>

    {{-- 4 thẻ thống kê trên cùng --}}
    <div class="row g-4 mb-4">
        {{-- Subjects --}}
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-info">
                        <h5 class="card-title mb-1">
                            24
                        </h5>
                        <p class="stats-text">Total Subjects</p>
                        <p class="mb-0 mt-1 text-success" style="font-size:12px;">↑ 3 new this month</p>
                    </div>
                    <div class="stats-icon change-success">
                        📚
                    </div>
                </div>
            </div>
        </div>

        {{-- Topics --}}
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-info">
                        <h5 class="card-title mb-1">
                            156
                        </h5>
                        <p class="stats-text">Active Topics</p>
                        <p class="mb-0 mt-1 text-primary" style="font-size:12px;">↑ 12 added recently</p>
                    </div>
                    <div class="stats-icon change-success">
                        📄
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions --}}
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-info">
                        <h5 class="card-title mb-1">
                            1,247
                        </h5>
                        <p class="stats-text">Questions Bank</p>
                        <p class="mb-0 mt-1 text-primary" style="font-size:12px;">↑ 89 this week</p>
                    </div>
                    <div class="stats-icon change-success">
                        ❓
                    </div>
                </div>
            </div>
        </div>

        {{-- Exams --}}
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-info">
                        <h5 class="card-title mb-1">
                            32
                        </h5>
                        <p class="stats-text">Active Exams</p>
                        <p class="mb-0 mt-1 text-purple" style="font-size:12px;color:#7c3aed;">5 scheduled soon</p>
                    </div>
                    <div class="stats-icon change-success">
                        📝
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="widget-container h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);font-size:20px;">
                            +
                        </div>
                        <h6 class="fw-semibold mb-1">Create Course</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Start a new course for your students</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-container h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);font-size:20px;">
                            📝
                        </div>
                        <h6 class="fw-semibold mb-1">Add Assignment</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Create homework or projects</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-container h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);font-size:20px;">
                            📅
                        </div>
                        <h6 class="fw-semibold mb-1">Schedule Exam</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Set up a new assessment</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-container h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#fce7f3,#fbcfe8);font-size:20px;">
                            📊
                        </div>
                        <h6 class="fw-semibold mb-1">View Reports</h6>
                        <p class="text-muted mb-0" style="font-size:13px;">Check student performance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics period selector --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title mb-0">Analytics Period</h5>
            <div class="btn-group">
                <a href="{{ route('admin.statistics.usage-duration', ['period' => '7days']) }}"
                   class="btn btn-sm {{ $period == '7days' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    7 Days
                </a>
                <a href="{{ route('admin.statistics.usage-duration', ['period' => '30days']) }}"
                   class="btn btn-sm {{ $period == '30days' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    30 Days
                </a>
                <a href="{{ route('admin.statistics.usage-duration', ['period' => '90days']) }}"
                   class="btn btn-sm {{ $period == '90days' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    90 Days
                </a>
            </div>
        </div>
    </div>

    {{-- Usage by role --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                📊 Activity by Role
            </h5>
            <div class="row g-3">
                @foreach($usageByRole as $role)
                    <div class="col-lg-4 col-md-6">
                        <div class="widget-container h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0 text-capitalize">{{ $role->role_name }}</h6>
                                <span style="font-size:24px;">
                                    @if($role->role_name == 'admin') 👨‍💼
                                    @elseif($role->role_name == 'teacher') 👨‍🏫
                                    @else 👨‍🎓
                                    @endif
                                </span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted" style="font-size:13px;">Users</span>
                                <span class="fw-bold">{{ number_format($role->user_count) }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted" style="font-size:13px;">Total Actions</span>
                                <span class="fw-bold text-primary">{{ number_format($role->total_actions) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted" style="font-size:13px;">Avg per User</span>
                                <span class="fw-bold text-purple" style="color:#7c3aed;">
                                    {{ number_format($role->avg_actions_per_user, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Daily activity chart --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">📈 Daily Activity Trends</h5>
            <div class="chart-container">
                <canvas id="dailyUsageChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top active users --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">🏆 Most Active Users</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">User</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                        <th scope="col">Level</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($topActiveUsers as $index => $user)
                        <tr>
                            <td>
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold"
                                      style="width:36px;height:36px;
                                          @if($index == 0)
                                              background:linear-gradient(135deg,#facc15,#f97316);color:#fff;
                                          @elseif($index == 1)
                                              background:linear-gradient(135deg,#d4d4d8,#a1a1aa);color:#fff;
                                          @elseif($index == 2)
                                              background:linear-gradient(135deg,#fed7aa,#f97316);color:#fff;
                                          @else
                                              background:#e0e7ff;color:#4338ca;
                                          @endif
                                      ">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $user->user->name ?? 'N/A' }}</strong>
                            </td>
                            <td class="text-muted">{{ $user->user->email ?? 'N/A' }}</td>
                            <td>
                                <span class="fw-bold text-primary fs-5">{{ number_format($user->action_count) }}</span>
                            </td>
                            <td>
                                @if($user->action_count > 1000)
                                    <span class="badge rounded-pill bg-gradient"
                                          style="background:linear-gradient(135deg,#8b5cf6,#ec4899);color:#fff;">
                                        🔥 Super Active
                                    </span>
                                @elseif($user->action_count > 500)
                                    <span class="badge rounded-pill"
                                          style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;">
                                        ✨ Very Active
                                    </span>
                                @elseif($user->action_count > 100)
                                    <span class="badge rounded-pill"
                                          style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;">
                                        👍 Active
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary">
                                        📊 Regular
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dailyData = @json($dailyUsage);

            const ctx = document.getElementById('dailyUsageChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dailyData.map(d => d.date),
                    datasets: [
                        {
                            label: 'Total Activity',
                            data: dailyData.map(d => d.total_actions),
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 2,
                            borderRadius: 8,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Active Users',
                            data: dailyData.map(d => d.active_users),
                            type: 'line',
                            borderColor: 'rgba(168, 85, 247, 1)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(168, 85, 247, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
@endpush
