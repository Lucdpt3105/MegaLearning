@extends('admin.layout')

@section('title', 'Ngân hàng câu hỏi')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ngân hàng câu hỏi</h1>
        <a href="{{ route('admin.questions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Thêm câu hỏi
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Câu hỏi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Độ khó</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $demoQuestions = [
                        ['question' => 'Tính đạo hàm của hàm số y = x² + 2x - 1?', 'type' => 'Trắc nghiệm', 'subject' => 'Toán', 'level' => 'Dễ'],
                        ['question' => 'Định luật Ohm là gì?', 'type' => 'Trắc nghiệm', 'subject' => 'Vật lý', 'level' => 'Trung bình'],
                        ['question' => 'Phản ứng oxi hóa khử là gì?', 'type' => 'Tự luận', 'subject' => 'Hóa học', 'level' => 'Khó'],
                        ['question' => 'What is the past tense of "go"?', 'type' => 'Trắc nghiệm', 'subject' => 'Tiếng Anh', 'level' => 'Dễ'],
                    ];
                @endphp
                @forelse($demoQuestions as $q)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($q['question'], 50) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $q['type'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $q['subject'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($q['level'] == 'Dễ') bg-green-100 text-green-700
                            @elseif($q['level'] == 'Trung bình') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $q['level'] }}
                        </span>
                    </td>
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
