@extends('admin.layout')

@section('title', 'Kết quả thi')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kết quả thi</h1>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Học sinh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bài thi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Điểm số</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian nộp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoResults = [
                        ['student' => 'Nguyễn Văn A', 'exam' => 'Kiểm tra Toán Giữa kỳ', 'score' => 85, 'time' => '2 giờ trước', 'status' => 'Đã chấm'],
                        ['student' => 'Trần Thị B', 'exam' => 'Kiểm tra Vật lý', 'score' => 72, 'time' => '5 giờ trước', 'status' => 'Đã chấm'],
                        ['student' => 'Lê Văn C', 'exam' => 'Kiểm tra Hóa học', 'score' => 0, 'time' => '1 ngày trước', 'status' => 'Đang chấm'],
                    ];
                @endphp
                @forelse($demoResults as $result)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $result['student'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result['exam'] }}</td>
                    <td class="px-6 py-4 text-sm font-semibold 
                        @if($result['score'] >= 80) text-green-600
                        @elseif($result['score'] >= 50) text-blue-600
                        @else text-red-600 @endif">
                        {{ $result['status'] == 'Đã chấm' ? $result['score'] . '/100' : '--' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $result['time'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $result['status'] == 'Đã chấm' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $result['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Xem chi tiết</button>
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
