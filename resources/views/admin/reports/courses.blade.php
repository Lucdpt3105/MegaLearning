@extends('admin.layout')

@section('title', 'Thống kê lớp học')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Thống kê lớp học</h1>
        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i data-feather="download" class="w-4 h-4 inline"></i> Xuất báo cáo
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Tổng số lớp học</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">35</div>
            <div class="text-xs text-green-600 mt-1">↑ 5 lớp mới</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Đang diễn ra</div>
            <div class="text-3xl font-bold text-green-600 mt-2">28</div>
            <div class="text-xs text-gray-500 mt-1">80% tổng số</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Đã kết thúc</div>
            <div class="text-3xl font-bold text-gray-600 mt-2">7</div>
            <div class="text-xs text-gray-500 mt-1">20% tổng số</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Tỷ lệ hoàn thành</div>
            <div class="text-3xl font-bold text-purple-600 mt-2">85%</div>
            <div class="text-xs text-green-600 mt-1">↑ 8% so với kỳ trước</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Biểu đồ thống kê</h2>
        <div class="h-64 flex items-center justify-center text-gray-500">
            Biểu đồ sẽ hiển thị tại đây
        </div>
    </div>
</div>
@endsection
