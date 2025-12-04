@extends('admin.layout')

@section('title', 'Xếp hạng Học sinh')
@section('page-title', 'UC-SYS-004: Xếp hạng Học sinh')
@section('page-description', 'Bảng xếp hạng được tính toán tự động bởi hệ thống')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
                <select name="class_room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tất cả các lớp</option>
                    @foreach($classRooms as $class)
                        <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
                <select name="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tất cả môn học</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    🔍 Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Rankings Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">🏆 Bảng Xếp hạng</h3>
            <span class="text-sm text-gray-500">
                Cập nhật lần cuối: {{ $rankings->first()->calculated_at ?? 'Chưa có dữ liệu' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hạng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Học sinh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lớp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">GPA</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Điểm TB</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bài thi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tỷ lệ đạt</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Điểm danh</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rankings as $ranking)
                        <tr class="hover:bg-gray-50 {{ $ranking->rank <= 3 ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    @if($ranking->rank == 1)
                                        <span class="text-3xl">🥇</span>
                                    @elseif($ranking->rank == 2)
                                        <span class="text-3xl">🥈</span>
                                    @elseif($ranking->rank == 3)
                                        <span class="text-3xl">🥉</span>
                                    @else
                                        <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-700 font-bold">
                                            {{ $ranking->rank }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $ranking->student->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $ranking->student->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $ranking->classRoom->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $ranking->subject->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold text-blue-600">{{ number_format($ranking->gpa, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold text-green-600">{{ number_format($ranking->average_score, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $ranking->total_exams_passed }}/{{ $ranking->total_exams_taken }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $passRate = $ranking->total_exams_taken > 0 
                                        ? round(($ranking->total_exams_passed / $ranking->total_exams_taken) * 100, 1)
                                        : 0;
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $passRate >= 80 ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $passRate >= 50 && $passRate < 80 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $passRate < 50 ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $passRate }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $ranking->attendance_rate >= 80 ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $ranking->attendance_rate >= 50 && $ranking->attendance_rate < 80 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $ranking->attendance_rate < 50 ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ number_format($ranking->attendance_rate, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-6xl mb-4">📊</span>
                                    <p class="text-gray-500 font-medium">Chưa có dữ liệu xếp hạng</p>
                                    <p class="text-sm text-gray-400 mt-2">
                                        Hệ thống sẽ tự động tính toán xếp hạng khi có dữ liệu điểm
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rankings->hasPages())
            <div class="mt-6">
                {{ $rankings->links() }}
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <span class="text-2xl">ℹ️</span>
            <div>
                <h4 class="font-semibold text-blue-800 mb-2">Về UC-SYS-004: Thống kê điểm số và xếp hạng</h4>
                <p class="text-sm text-blue-700">
                    Bảng xếp hạng này được tính toán tự động bởi một tác vụ nền (batch job) chạy định kỳ. 
                    Hệ thống tự động tính toán GPA, điểm trung bình, và xếp hạng dựa trên kết quả các bài thi.
                </p>
            </div>
        </div>
    </div>
@endsection
