@extends('admin.layout')

@section('title', 'Chủ đề thảo luận')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Chủ đề thảo luận</h1>
        <a href="{{ route('admin.forums.topics.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Thêm chủ đề
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người tạo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số bài viết</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoTopics = [
                        ['title' => 'Hướng dẫn giải bài tập Toán nâng cao', 'author' => 'Nguyễn Văn A', 'posts' => 24, 'status' => 'Hoạt động'],
                        ['title' => 'Thảo luận về đề thi Vật lý', 'author' => 'Trần Thị B', 'posts' => 18, 'status' => 'Hoạt động'],
                        ['title' => 'Chia sẻ tài liệu Hóa học', 'author' => 'Lê Văn C', 'posts' => 7, 'status' => 'Đang chờ duyệt'],
                        ['title' => 'Câu hỏi về bài tập Tiếng Anh', 'author' => 'Phạm Thị D', 'posts' => 32, 'status' => 'Hoạt động'],
                    ];
                @endphp
                @forelse($demoTopics as $topic)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $topic['title'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">Đã được xem 156 lần</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $topic['author'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $topic['posts'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $topic['status'] == 'Hoạt động' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $topic['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Xem</button>
                        <button class="text-red-600 hover:text-red-800">Xóa</button>
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
