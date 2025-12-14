@extends('admin.layout')

@section('title', 'Tham gia Hoạt động')
@section('page-title', 'Thống kê Tham gia')
@section('page-description', 'Theo dõi mức độ tham gia của học sinh và giáo viên')

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
    .progress-bar {
        height: 0.5rem;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transition: width 0.5s ease;
    }
</style>
@endpush

@section('content')

<!-- Subject Statistics -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i data-feather="book-open" class="w-6 h-6 inline text-blue-600"></i>
        Thống kê theo Môn học
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjectStats as $subject)
            <div class="stat-card">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">{{ $subject->name }}</h4>
                        <p class="text-sm text-gray-600">
                            GV: {{ $subject->teacher ? $subject->teacher->name : 'Chưa có' }}
                        </p>
                    </div>
                    <div class="text-3xl">📚</div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Số lớp</span>
                        <span class="font-semibold text-gray-800">{{ $subject->class_rooms_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tổng học sinh</span>
                        <span class="font-semibold text-gray-800">{{ $subject->total_students ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Số bài thi</span>
                        <span class="font-semibold text-gray-800">{{ $subject->exams_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tài liệu</span>
                        <span class="font-semibold text-gray-800">{{ $subject->documents_count }}</span>
                    </div>
                </div>

                @php
                    $totalActivities = $subject->exams_count + $subject->documents_count + $subject->class_rooms_count;
                    $activityScore = min(100, $totalActivities * 5);
                @endphp
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Mức độ hoạt động</span>
                        <span>{{ $activityScore }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $activityScore }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                <p>Chưa có môn học nào</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Video Call Statistics -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i data-feather="video" class="w-6 h-6 inline text-green-600"></i>
        Thống kê Video Call
    </h3>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lớp học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Số cuộc gọi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tổng thời lượng</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">TB/cuộc gọi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Max tham gia</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($videoCallStats as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $stat->classRoom ? $stat->classRoom->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $stat->classRoom && $stat->classRoom->subject ? $stat->classRoom->subject->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ $stat->total_calls }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            {{ gmdate('H:i:s', $stat->total_duration ?? 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            {{ gmdate('H:i:s', $stat->avg_duration ?? 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $stat->max_participants }} người
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i data-feather="video-off" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>Chưa có cuộc gọi video nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Exam Participation -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i data-feather="file-text" class="w-6 h-6 inline text-purple-600"></i>
        Top 20 Bài thi được tham gia nhiều nhất
    </h3>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">STT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên bài thi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Số bài nộp</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($examParticipation as $index => $exam)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $exam->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $exam->subject ? $exam->subject->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $exam->total_submissions }} bài
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            @if($exam->status == 'published')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Đã xuất bản</span>
                            @elseif($exam->status == 'draft')
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Nháp</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $exam->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>Chưa có bài thi nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Completion Rate -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i data-feather="pie-chart" class="w-6 h-6 inline text-orange-600"></i>
        Tỷ lệ hoàn thành bài thi
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($completionRate as $rate)
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-semibold text-gray-800">
                        @if($rate->grading_status == 'graded')
                            Đã chấm điểm
                        @elseif($rate->grading_status == 'pending')
                            Đang chờ
                        @else
                            {{ ucfirst($rate->grading_status) }}
                        @endif
                    </h4>
                    <span class="text-3xl">
                        @if($rate->grading_status == 'graded')
                            ✅
                        @elseif($rate->grading_status == 'pending')
                            ⏳
                        @else
                            📝
                        @endif
                    </span>
                </div>
                <div class="text-4xl font-bold text-gray-800 mb-2">{{ $rate->count }}</div>
                <div class="text-sm text-gray-600">
                    {{ round(($rate->count / max($completionRate->sum('count'), 1)) * 100, 1) }}% tổng số
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize Feather icons
    feather.replace();
</script>
@endpush
