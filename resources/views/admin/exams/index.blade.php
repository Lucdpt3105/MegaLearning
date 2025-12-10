@extends('admin.layout')

@section('title', 'Danh sách bài thi')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Danh sách bài thi</h1>
        <a href="{{ route('admin.exams.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Tạo bài thi
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên bài thi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số câu hỏi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoExams = [
                        ['name' => 'Kiểm tra Toán Giữa kỳ 1', 'subject' => 'Toán học', 'duration' => '60 phút', 'questions' => 20, 'status' => 'Đang diễn ra'],
                        ['name' => 'Kiểm tra Vật lý Cuối kỳ', 'subject' => 'Vật lý', 'duration' => '90 phút', 'questions' => 30, 'status' => 'Sắp tới'],
                        ['name' => 'Kiểm tra Hóa học Thường xuyên', 'subject' => 'Hóa học', 'duration' => '45 phút', 'questions' => 15, 'status' => 'Đã kết thúc'],
                    ];
                @endphp
                @forelse($demoExams as $exam)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $exam['name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam['subject'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam['duration'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam['questions'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($exam['status'] == 'Đang diễn ra') bg-green-100 text-green-700
                            @elseif($exam['status'] == 'Sắp tới') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $exam['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Xem</button>
                        <button class="text-green-600 hover:text-green-800 mr-3">Sửa</button>
                        <button class="text-red-600 hover:text-red-800">Xóa</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
