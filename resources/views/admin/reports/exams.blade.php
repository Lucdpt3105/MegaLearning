@extends('admin.layout')

@section('title', 'Thống kê bài thi')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Thống kê bài thi</h1>
        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i data-feather="download" class="w-4 h-4 inline"></i> Xuất báo cáo
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Tổng số bài thi</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">0</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Đã làm bài</div>
            <div class="text-3xl font-bold text-green-600 mt-2">0</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Điểm trung bình</div>
            <div class="text-3xl font-bold text-orange-600 mt-2">0</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Tỷ lệ đạt</div>
            <div class="text-3xl font-bold text-purple-600 mt-2">0%</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Biểu đồ phân bố điểm</h2>
        <div class="h-64 flex items-center justify-center text-gray-500">
            Biểu đồ sẽ hiển thị tại đây
        </div>
    </div>
</div>
@endsection
