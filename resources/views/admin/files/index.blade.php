@extends('admin.layout')

@section('title', 'Quản lý file')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý file</h1>
        <a href="{{ route('admin.files.upload') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="upload" class="w-4 h-4 inline"></i> Tải lên file
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên file</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kích thước</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người tải lên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày tải lên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoFiles = [
                        ['name' => 'Đề cương ôn tập Toán.pdf', 'type' => 'PDF', 'size' => '2.3 MB', 'uploader' => 'Giáo viên A', 'date' => '09/12/2024'],
                        ['name' => 'Bài giảng Vật lý.pptx', 'type' => 'PowerPoint', 'size' => '5.1 MB', 'uploader' => 'Giáo viên B', 'date' => '08/12/2024'],
                        ['name' => 'Đề thi thử Hóa học.docx', 'type' => 'Word', 'size' => '1.2 MB', 'uploader' => 'Giáo viên C', 'date' => '07/12/2024'],
                        ['name' => 'Video bài giảng Tiếng Anh.mp4', 'type' => 'Video', 'size' => '45.8 MB', 'uploader' => 'Giáo viên D', 'date' => '06/12/2024'],
                    ];
                @endphp
                @forelse($demoFiles as $file)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <i data-feather="file" class="w-5 h-5 text-gray-400 mr-2"></i>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $file['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $file['type'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $file['type'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $file['size'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $file['uploader'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $file['date'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">
                            <i data-feather="download" class="w-4 h-4"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Chưa có file nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
