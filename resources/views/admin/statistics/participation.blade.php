@extends('admin.layout')

@section('title', 'Thống kê Tham gia')
@section('page-title', '📈 Thống kê Tham gia Hoạt động')
@section('page-description', 'Phân tích mức độ tham gia của học sinh theo môn học và lớp học')

@push('styles')
<style>
    .subject-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .subject-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .metric-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0.75rem;
        padding: 1rem;
        color: white;
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
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Tổng môn học</p>
                <h3 class="text-3xl font-bold">{{ number_format($subjectStats->count()) }}</h3>
            </div>
            <div class="text-4xl opacity-80">📚</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Tổng lớp học</p>
                <h3 class="text-3xl font-bold">{{ number_format($subjectStats->sum('class_rooms_count')) }}</h3>
            </div>
            <div class="text-4xl opacity-80">🏫</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Tổng học sinh</p>
                <h3 class="text-3xl font-bold">{{ number_format($subjectStats->sum('total_students')) }}</h3>
            </div>
            <div class="text-4xl opacity-80">👨‍🎓</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm mb-1">Tổng đề thi</p>
                <h3 class="text-3xl font-bold">{{ number_format($subjectStats->sum('exams_count')) }}</h3>
            </div>
            <div class="text-4xl opacity-80">📝</div>
        </div>
    </div>
</div>

<!-- Subjects Statistics -->
<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i data-feather="book-open" class="w-5 h-5 inline text-blue-600"></i>
        Thống kê chi tiết theo môn học
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($subjectStats as $subject)
        <div class="subject-card">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 text-lg">{{ $subject->name }}</h4>
                <span class="text-3xl">📖</span>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Giáo viên</span>
                    <span class="font-semibold text-gray-800">{{ $subject->teacher->name ?? 'N/A' }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Số lớp</span>
                    <span class="text-lg font-bold text-blue-600">{{ $subject->class_rooms_count }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Học sinh</span>
                    <span class="text-lg font-bold text-green-600">{{ $subject->total_students ?? 0 }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Đề thi</span>
                    <span class="text-lg font-bold text-purple-600">{{ $subject->exams_count }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tài liệu</span>
                    <span class="text-lg font-bold text-orange-600">{{ $subject->documents_count }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Video Call Statistics -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i data-feather="video" class="w-5 h-5 inline text-red-600"></i>
        Thống kê Video Call
    </h3>
    
    @if($videoCallStats->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Lớp học</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Số buổi</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Tổng thời gian (phút)</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">TB thời gian</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Max người tham gia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($videoCallStats as $stat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900">{{ $stat->classRoom->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm text-gray-600">{{ $stat->classRoom->subject->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-bold text-blue-600">{{ $stat->total_calls }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-bold text-green-600">{{ number_format($stat->total_duration ?? 0) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-bold text-purple-600">{{ number_format($stat->avg_duration ?? 0, 1) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-bold text-orange-600">{{ $stat->max_participants }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-2">📹</div>
        <p class="text-gray-600">Chưa có dữ liệu video call</p>
    </div>
    @endif
</div>

<!-- Exam Participation -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Top Exams -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i data-feather="trending-up" class="w-5 h-5 inline text-green-600"></i>
                Top 20 đề thi có nhiều bài nộp nhất
            </h3>
        </div>
        
        <div class="p-6 space-y-3">
            @foreach($examParticipation as $index => $exam)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-3 flex-1">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-white
                        {{ $index < 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 'bg-gray-400' }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $exam->title }}</p>
                        <p class="text-sm text-gray-500">{{ $exam->subject->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">{{ $exam->total_submissions }}</p>
                    <p class="text-xs text-gray-500">bài nộp</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Completion Rate -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i data-feather="pie-chart" class="w-5 h-5 inline text-purple-600"></i>
            Tỷ lệ hoàn thành bài thi
        </h3>
        
        <div class="chart-container">
            <canvas id="completionChart"></canvas>
        </div>
        
        <div class="mt-4 space-y-2">
            @foreach($completionRate as $rate)
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                <span class="text-sm font-medium text-gray-700">
                    {{ ucfirst(str_replace('_', ' ', $rate->grading_status)) }}
                </span>
                <span class="font-bold 
                    {{ $rate->grading_status == 'graded' ? 'text-green-600' : 
                       ($rate->grading_status == 'pending' ? 'text-yellow-600' : 'text-blue-600') }}">
                    {{ $rate->count }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
    
    // Completion Rate Chart
    const completionData = @json($completionRate);
    const ctx = document.getElementById('completionChart');
    
    if (ctx) {
        const colors = {
            'graded': '#10b981',
            'pending': '#f59e0b',
            'auto_graded': '#3b82f6'
        };
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: completionData.map(d => ucfirst(d.grading_status.replace('_', ' '))),
                datasets: [{
                    data: completionData.map(d => d.count),
                    backgroundColor: completionData.map(d => colors[d.grading_status] || '#6b7280'),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
</script>
@endpush
