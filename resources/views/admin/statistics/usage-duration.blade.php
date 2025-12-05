@extends('admin.layout')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-description', 'Your teaching hub for managing courses and tracking student progress')

@push('styles')
<style>
    /* ClassPoint Style - Modern LMS UI */
    body {
        background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    .chart-container {
        position: relative;
        height: 350px;
    }
    
    /* Pastel Gradient Backgrounds */
    .gradient-indigo {
        background: linear-gradient(135deg, #E0E7FF 0%, #C7D2FE 100%);
    }
    
    .gradient-purple {
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
    }
    
    .gradient-blue {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    }
    
    .gradient-pink {
        background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%);
    }
    
    /* Soft Card Shadow */
    .card-soft {
        box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08);
        transition: all 0.3s ease;
    }
    
    .card-soft:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.15);
    }
    
    /* Icon Styles */
    .icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    /* Smooth Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Stat Card Number Animation */
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Quick Action Cards */
    .quick-action-card {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover {
        border-color: #A5B4FC;
        background: linear-gradient(135deg, #FFFFFF 0%, #F5F3FF 100%);
    }
    
    /* Search Bar Styling */
    .search-bar {
        background: white;
        border-radius: 24px;
        border: 2px solid #E0E7FF;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .search-bar:focus-within {
        border-color: #A5B4FC;
        box-shadow: 0 0 0 4px rgba(165, 180, 252, 0.15);
    }
</style>
@endpush

@section('content')
    <!-- Welcome Header -->
    <div class="mb-8 animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Teacher Dashboard</h1>
                <p class="text-lg text-gray-600">Welcome back! Here's what's happening with your classes today.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                    👨‍🏫
                </div>
            </div>
        </div>
        
        <!-- Full-width Search Bar -->
        <div class="search-bar flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" placeholder="Search for students, courses, or topics..." 
                   class="flex-1 outline-none text-gray-700 placeholder-gray-400 bg-transparent">
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Subjects Card -->
        <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-circle gradient-indigo">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-2">Total Subjects</h3>
            <div class="stat-number">24</div>
            <p class="text-sm text-green-600 mt-2">↑ 3 new this month</p>
        </div>

        <!-- Topics Card -->
        <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-circle gradient-purple">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-2">Active Topics</h3>
            <div class="stat-number">156</div>
            <p class="text-sm text-blue-600 mt-2">↑ 12 added recently</p>
        </div>

        <!-- Questions Card -->
        <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-circle gradient-blue">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-2">Questions Bank</h3>
            <div class="stat-number">1,247</div>
            <p class="text-sm text-indigo-600 mt-2">↑ 89 this week</p>
        </div>

        <!-- Exams Card -->
        <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-circle gradient-pink">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-2">Active Exams</h3>
            <div class="stat-number">32</div>
            <p class="text-sm text-purple-600 mt-2">5 scheduled soon</p>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Create Course -->
            <div class="quick-action-card card-soft cursor-pointer">
                <div class="icon-circle gradient-indigo mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Create Course</h3>
                <p class="text-sm text-gray-600">Start a new course for your students</p>
            </div>

            <!-- Add Assignment -->
            <div class="quick-action-card card-soft cursor-pointer">
                <div class="icon-circle gradient-purple mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Add Assignment</h3>
                <p class="text-sm text-gray-600">Create homework or projects</p>
            </div>

            <!-- Schedule Exam -->
            <div class="quick-action-card card-soft cursor-pointer">
                <div class="icon-circle gradient-blue mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Schedule Exam</h3>
                <p class="text-sm text-gray-600">Set up a new assessment</p>
            </div>

            <!-- View Reports -->
            <div class="quick-action-card card-soft cursor-pointer">
                <div class="icon-circle gradient-pink mb-4">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">View Reports</h3>
                <p class="text-sm text-gray-600">Check student performance</p>
            </div>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="mb-6">
        <div class="bg-white rounded-3xl card-soft p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Analytics Period</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '7days']) }}" 
                       class="px-5 py-2.5 rounded-2xl font-medium transition-all {{ $period == '7days' ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        7 Days
                    </a>
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '30days']) }}" 
                       class="px-5 py-2.5 rounded-2xl font-medium transition-all {{ $period == '30days' ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        30 Days
                    </a>
                    <a href="{{ route('admin.statistics.usage-duration', ['period' => '90days']) }}" 
                       class="px-5 py-2.5 rounded-2xl font-medium transition-all {{ $period == '90days' ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        90 Days
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage by Role -->
    <div class="bg-white rounded-3xl card-soft p-8 mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-2xl">📊</span> Activity by Role
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($usageByRole as $role)
                <div class="rounded-3xl p-6 border-2 border-transparent hover:border-indigo-200 transition-all
                    {{ $role->role_name == 'admin' ? 'gradient-indigo' : '' }}
                    {{ $role->role_name == 'teacher' ? 'gradient-purple' : '' }}
                    {{ $role->role_name == 'student' ? 'gradient-blue' : '' }}">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-800">{{ ucfirst($role->role_name) }}</h4>
                        <span class="text-4xl">
                            @if($role->role_name == 'admin') 👨‍💼
                            @elseif($role->role_name == 'teacher') 👨‍🏫
                            @else 👨‍🎓
                            @endif
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Users</span>
                            <span class="text-2xl font-bold text-gray-800">{{ number_format($role->user_count) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Total Actions</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ number_format($role->total_actions) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Avg per User</span>
                            <span class="text-2xl font-bold text-purple-600">{{ number_format($role->avg_actions_per_user, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Usage Chart -->
    <div class="bg-white rounded-3xl card-soft p-8 mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-2xl">📈</span> Daily Activity Trends
        </h3>
        <div class="chart-container">
            <canvas id="dailyUsageChart"></canvas>
        </div>
    </div>

    <!-- Top Active Users -->
    <div class="bg-white rounded-3xl card-soft p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-2xl">🏆</span> Most Active Users
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider rounded-tl-2xl">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider rounded-tr-2xl">Level</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($topActiveUsers as $index => $user)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="flex items-center justify-center w-10 h-10 rounded-2xl font-bold text-sm
                                    {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-400 text-white shadow-lg' : '' }}
                                    {{ $index == 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white shadow-md' : '' }}
                                    {{ $index == 2 ? 'bg-gradient-to-br from-orange-300 to-orange-400 text-white shadow-md' : '' }}
                                    {{ $index > 2 ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">{{ $user->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->user->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xl font-bold text-indigo-600">{{ number_format($user->action_count) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->action_count > 1000)
                                    <span class="px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-purple-400 to-pink-400 text-white shadow-md">
                                        🔥 Super Active
                                    </span>
                                @elseif($user->action_count > 500)
                                    <span class="px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-green-400 to-emerald-400 text-white shadow-md">
                                        ✨ Very Active
                                    </span>
                                @elseif($user->action_count > 100)
                                    <span class="px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-blue-400 to-indigo-400 text-white shadow-md">
                                        👍 Active
                                    </span>
                                @else
                                    <span class="px-4 py-2 text-xs font-bold rounded-full bg-gray-200 text-gray-700">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyUsage);
    
    // ClassPoint-style Chart Configuration
    new Chart(document.getElementById('dailyUsageChart'), {
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
                    pointRadius: 5,
                    pointHoverRadius: 7,
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
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600',
                            family: 'Inter, sans-serif'
                        },
                        color: '#4B5563'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1F2937',
                    bodyColor: '#4B5563',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        labelColor: function(context) {
                            return {
                                borderColor: context.dataset.borderColor,
                                backgroundColor: context.dataset.backgroundColor,
                                borderWidth: 2,
                                borderRadius: 4,
                            };
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        color: '#6B7280'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Total Actions',
                        font: {
                            size: 13,
                            weight: '600',
                            family: 'Inter, sans-serif'
                        },
                        color: '#4B5563'
                    },
                    grid: {
                        color: 'rgba(229, 231, 235, 0.5)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        color: '#6B7280'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Active Users',
                        font: {
                            size: 13,
                            weight: '600',
                            family: 'Inter, sans-serif'
                        },
                        color: '#4B5563'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        color: '#6B7280'
                    }
                }
            }
        }
    });
});
</script>
@endpush
