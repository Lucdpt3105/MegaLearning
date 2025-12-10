@extends('admin.layout')

@section('title', 'Kiểm duyệt')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kiểm duyệt nội dung</h1>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội dung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người báo cáo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lý do</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoReports = [
                        ['content' => 'Bài viết có nội dung không phù hợp...', 'reporter' => 'Nguyễn Văn A', 'reason' => 'Spam', 'status' => 'Đang xử lý'],
                        ['content' => 'Từ ngữ không lịch sự trong bình luận...', 'reporter' => 'Trần Thị B', 'reason' => 'Vi phạm quy định', 'status' => 'Chờ xử lý'],
                    ];
                @endphp
                @forelse($demoReports as $report)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($report['content'], 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report['reporter'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report['reason'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $report['status'] == 'Đang xử lý' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $report['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-green-600 hover:text-green-800 mr-3">Chấp nhận</button>
                        <button class="text-red-600 hover:text-red-800">Từ chối</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Không có báo cáo nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
