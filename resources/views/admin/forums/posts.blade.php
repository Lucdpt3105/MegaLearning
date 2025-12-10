@extends('admin.layout')

@section('title', 'Bài viết')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bài viết diễn đàn</h1>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội dung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người đăng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chủ đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoPosts = [
                        ['content' => 'Mình có thể giải thích cách giải bài tập này...', 'author' => 'Nguyễn Văn A', 'topic' => 'Hướng dẫn giải bài tập', 'time' => '2 giờ trước'],
                        ['content' => 'Theo mình thì cách làm tốt nhất là...', 'author' => 'Trần Thị B', 'topic' => 'Thảo luận đề thi', 'time' => '5 giờ trước'],
                        ['content' => 'Có ai có tài liệu này không?', 'author' => 'Lê Văn C', 'topic' => 'Chia sẻ tài liệu', 'time' => '1 ngày trước'],
                    ];
                @endphp
                @forelse($demoPosts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($post['content'], 60) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $post['author'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $post['topic'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $post['time'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">Sửa</button>
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
