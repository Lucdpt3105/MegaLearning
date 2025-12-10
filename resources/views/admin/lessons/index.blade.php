@extends('admin.layout')

@section('title', 'Bài học')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý bài học</h1>
        <a href="{{ route('admin.lessons.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Thêm bài học
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên bài học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khóa học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ tự</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có dữ liệu</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
