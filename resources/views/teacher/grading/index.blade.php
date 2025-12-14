@extends('layouts.app')

@section('title', 'Chấm điểm')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Chấm điểm 📊</h1>
                <p class="text-gray-600 mt-1">Quản lý chấm điểm bài thi</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-6 border-2 border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-sm font-medium">Chờ chấm</p>
                    <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-16 h-16 bg-yellow-200 rounded-full flex items-center justify-center">
                    <span class="text-3xl">⏳</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border-2 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 text-sm font-medium">Đã chấm</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['graded'] }}</p>
                </div>
                <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center">
                    <span class="text-3xl">✅</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 text-sm font-medium">Chấm tự động</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['auto_graded'] }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center">
                    <span class="text-3xl">🤖</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('teacher.grading.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái chấm</label>
                <select name="grading_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('grading_status') == 'pending' ? 'selected' : '' }}>Chờ chấm</option>
                    <option value="partially_graded" {{ request('grading_status') == 'partially_graded' ? 'selected' : '' }}>Chấm một phần</option>
                    <option value="graded" {{ request('grading_status') == 'graded' ? 'selected' : '' }}>Đã chấm</option>
                    <option value="auto_graded" {{ request('grading_status') == 'auto_graded' ? 'selected' : '' }}>Chấm tự động</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Đề thi</label>
                <select name="exam_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tất cả</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->title }} - {{ $exam->subject ? $exam->subject->name : 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Lọc
                </button>
                <a href="{{ route('teacher.grading.index') }}" class="ml-2 px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>

    <!-- Submissions List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học sinh
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Đề thi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian nộp
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Điểm
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($submission->student->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $submission->student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $submission->exam->title }}</div>
                            <div class="text-sm text-gray-500">{{ $submission->exam->subject ? $submission->exam->subject->name : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($submission->grading_status === 'pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Chờ chấm
                                </span>
                            @elseif($submission->grading_status === 'partially_graded')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    📝 Chấm một phần
                                </span>
                            @elseif($submission->grading_status === 'graded')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Đã chấm
                                </span>
                            @elseif($submission->grading_status === 'auto_graded')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    🤖 Tự động
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($submission->score !== null)
                                <span class="text-lg font-bold text-indigo-600">
                                    {{ number_format($submission->score, 1) }}
                                </span>
                                <span class="text-sm text-gray-500">/{{ $submission->exam->total_points }}</span>
                            @else
                                <span class="text-gray-400">Chưa chấm</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('teacher.grading.show', $submission) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                📋 Xem
                            </a>
                            @if($submission->grading_status === 'pending')
                                <form action="{{ route('teacher.grading.auto-grade', $submission) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                        🤖 Chấm tự động
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-6xl mb-4">📝</div>
                            <p class="text-lg">Không có bài thi nào cần chấm</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($submissions->hasPages())
    <div class="mt-6">
        {{ $submissions->links() }}
    </div>
    @endif
</div>
@endsection
