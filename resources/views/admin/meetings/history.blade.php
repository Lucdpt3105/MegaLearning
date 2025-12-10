@extends('admin.layout')

@section('title', 'Lịch sử họp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Lịch sử họp</h1>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian bắt đầu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian kết thúc</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số người tham gia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoHistory = [
                        ['title' => 'Họp phụ huynh lớp 10A', 'start' => '2024-12-09 14:00', 'end' => '2024-12-09 15:30', 'participants' => 45],
                        ['title' => 'Hướng dẫn làm bài tập Toán', 'start' => '2024-12-08 16:00', 'end' => '2024-12-08 17:00', 'participants' => 32],
                        ['title' => 'Ôn thi cuối kỳ Vật lý', 'start' => '2024-12-07 10:00', 'end' => '2024-12-07 11:30', 'participants' => 28],
                    ];
                @endphp
                @forelse($demoHistory as $meeting)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $meeting['title'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting['start'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting['end'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting['participants'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Xem ghi hình</button>
                        <button class="text-green-600 hover:text-green-800">Báo cáo</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
