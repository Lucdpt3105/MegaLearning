@extends('admin.layout')

@section('title', 'Kết quả thi')
@section('page-title', 'Kết quả thi')
@section('page-description', 'Quản lý kết quả thi của tất cả học sinh')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Tổng bài nộp</div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Đã chấm</div>
        <div class="text-3xl font-bold text-green-600">{{ $stats['graded'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Chờ chấm</div>
        <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Điểm TB</div>
        <div class="text-3xl font-bold text-blue-600">
            {{ $stats['average_score'] ? number_format($stats['average_score'], 2) : 'N/A' }}
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.exam-results.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
            <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Đề thi</label>
            <select name="exam_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                        {{ $exam->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái chấm</label>
            <select name="grading_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('grading_status') == 'pending' ? 'selected' : '' }}>Chờ chấm</option>
                <option value="graded" {{ request('grading_status') == 'graded' ? 'selected' : '' }}>Đã chấm</option>
                <option value="auto_graded" {{ request('grading_status') == 'auto_graded' ? 'selected' : '' }}>Chấm tự động</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm học sinh</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                   placeholder="Nhập tên học sinh...">
        </div>

        <div class="md:col-span-4 flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-feather="search" class="w-4 h-4 inline"></i> Lọc
            </button>
            <a href="{{ route('admin.exam-results.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i data-feather="x" class="w-4 h-4 inline"></i> Xóa bộ lọc
            </a>
        </div>
    </form>
</div>

<!-- Results Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Học sinh</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bài thi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Môn học</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Điểm số</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Thời gian nộp</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($submissions as $index => $submission)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold">
                                {{ substr($submission->student ? $submission->student->name : 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $submission->student ? $submission->student->name : 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $submission->student ? $submission->student->email : '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $submission->exam->title }}
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        {{ $submission->exam->subject ? $submission->exam->subject->name : 'N/A' }}
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        @if($submission->score !== null)
                            <span class="text-lg font-bold text-blue-600">
                                {{ number_format($submission->score, 2) }}
                            </span>
                            <span class="text-sm text-gray-500">/10</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        @if($submission->submitted_at)
                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-yellow-600">Đang làm</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        @if($submission->grading_status === 'graded')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Đã chấm
                            </span>
                        @elseif($submission->grading_status === 'auto_graded')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                Chấm tự động
                            </span>
                        @elseif($submission->grading_status === 'pending' && $submission->status === 'submitted')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                Chờ chấm
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($submission->grading_status) }}
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.exam-results.show', $submission) }}" 
                           class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition inline-flex"
                           title="Xem chi tiết">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i data-feather="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có kết quả thi</h3>
                        <p class="text-gray-600">Chưa có bài nộp nào trong hệ thống</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($submissions->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $submissions->links() }}
    </div>
    @endif
</div>

@endsection

