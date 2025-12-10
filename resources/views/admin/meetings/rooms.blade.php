@extends('admin.layout')

@section('title', 'Phòng họp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Phòng họp</h1>
        <a href="{{ route('admin.meetings.rooms.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Tạo phòng họp
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên phòng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chủ phòng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số người tham gia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoRooms = [
                        ['name' => 'Phòng học Toán 10A', 'host' => 'Giáo viên Nguyễn', 'participants' => 28, 'status' => 'Đang hoạt động'],
                        ['name' => 'Phòng họp giáo viên', 'host' => 'Admin', 'participants' => 12, 'status' => 'Đang hoạt động'],
                        ['name' => 'Phòng thi online Vật lý', 'host' => 'Giáo viên Trần', 'participants' => 35, 'status' => 'Đã kết thúc'],
                    ];
                @endphp
                @forelse($demoRooms as $room)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $room['name'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">ID: #{{ rand(10000, 99999) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $room['host'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $room['participants'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $room['status'] == 'Đang hoạt động' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $room['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Tham gia</button>
                        <button class="text-red-600 hover:text-red-800">Đóng</button>
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
