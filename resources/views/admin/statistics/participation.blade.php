@extends('admin.layout')

@section('title', 'Thống kê Người tham gia')
@section('page-title', 'Thống kê Số người tham gia')
@section('page-description', 'Phân tích số lượng người tham gia theo môn học và hoạt động')

@section('content')
    <!-- Subject Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">📚 Thống kê theo Môn học</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Giáo viên</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Số lớp</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Học sinh</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Đề thi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tài liệu</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Mức độ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subjectStats as $subject)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $subject->name }}</div>
                                <div class="text-xs text-gray-500">{{ $subject->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $subject->teacher->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $subject->class_rooms_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    {{ $subject->total_students ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                                    {{ $subject->exams_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">
                                    {{ $subject->documents_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $totalActivity = $subject->class_rooms_count + ($subject->total_students ?? 0) + $subject->exams_count + $subject->documents_count;
                                @endphp
                                @if($totalActivity > 100)
                                    <span class="text-2xl" title="Rất cao">🔥</span>
                                @elseif($totalActivity > 50)
                                    <span class="text-2xl" title="Cao">⭐</span>
                                @elseif($totalActivity > 20)
                                    <span class="text-2xl" title="Trung bình">📊</span>
                                @else
                                    <span class="text-2xl" title="Thấp">📉</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Chưa có dữ liệu môn học
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Video Call Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">📹 Thống kê Video Call</h3>
        
        @if($videoCallStats->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700 mb-1">Tổng cuộc gọi</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $videoCallStats->sum('total_calls') }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-700 mb-1">Tổng thời lượng</p>
                    <p class="text-2xl font-bold text-green-800">{{ number_format($videoCallStats->sum('total_duration')) }} phút</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm text-purple-700 mb-1">Thời lượng TB</p>
                    <p class="text-2xl font-bold text-purple-800">{{ number_format($videoCallStats->avg('avg_duration'), 1) }} phút</p>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <p class="text-sm text-orange-700 mb-1">Người tham gia tối đa</p>
                    <p class="text-2xl font-bold text-orange-800">{{ $videoCallStats->max('max_participants') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lớp học</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Số cuộc gọi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tổng thời lượng</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">TB thời lượng</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Max người tham gia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($videoCallStats as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">
                                        {{ $stat->classRoom->subject->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $stat->classRoom->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-blue-600">
                                    {{ $stat->total_calls }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-green-600">
                                    {{ number_format($stat->total_duration) }} phút
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-purple-600">
                                    {{ number_format($stat->avg_duration, 1) }} phút
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-orange-600">
                                    {{ $stat->max_participants }} người
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-500">Chưa có dữ liệu video call</p>
            </div>
        @endif
    </div>

    <!-- Exam Participation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">📝 Thống kê Đề thi & Bài nộp</h3>
        
        <!-- Completion Rate -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @foreach($completionRate as $status)
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">
                        @if($status->grading_status == 'pending')
                            Đang chờ chấm
                        @elseif($status->grading_status == 'in_progress')
                            Đang chấm
                        @elseif($status->grading_status == 'completed')
                            Đã chấm xong
                        @else
                            {{ $status->grading_status }}
                        @endif
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($status->count) }}</p>
                </div>
            @endforeach
        </div>

        <!-- Top Exams -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đề thi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Số bài nộp</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Loại thi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($examParticipation as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $exam->title }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $exam->subject->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $exam->total_submissions }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $exam->exam_type == 'multiple_choice' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $exam->exam_type == 'multiple_choice' ? 'Trắc nghiệm' : 'Tự luận' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Chưa có dữ liệu đề thi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
