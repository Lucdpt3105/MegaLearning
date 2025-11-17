@extends('layouts.app')

@section('title', 'Chi tiết Câu hỏi')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Chi tiết Câu hỏi</h1>
            <p class="text-gray-600 mt-2">Xem thông tin chi tiết câu hỏi</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('teacher.questions.edit', $question) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                Sửa
            </a>
            <a href="{{ route('teacher.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                ← Quay lại
            </a>
        </div>
    </div>

    <!-- Question Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6 text-white">
            <div class="flex items-center space-x-3 mb-3">
                <!-- Type Badge -->
                <span class="px-3 py-1 text-sm font-semibold bg-white/20 rounded-full">
                    @if($question->type === 'multiple_choice') Trắc nghiệm
                    @elseif($question->type === 'true_false') Đúng/Sai
                    @elseif($question->type === 'essay') Tự luận
                    @else Điền khuyết
                    @endif
                </span>

                <!-- Difficulty Badge -->
                <span class="px-3 py-1 text-sm font-semibold bg-white/20 rounded-full">
                    @if($question->difficulty === 'easy') Dễ
                    @elseif($question->difficulty === 'medium') Trung bình
                    @else Khó
                    @endif
                </span>

                <!-- Points -->
                <span class="px-3 py-1 text-sm font-semibold bg-white/20 rounded-full">
                    {{ $question->points }} điểm
                </span>
            </div>

            <h2 class="text-xl font-bold">{{ $question->subject->name }}</h2>
            <p class="text-sm mt-1 opacity-90">Tạo bởi {{ $question->creator->name }} • {{ $question->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Question Content -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Nội dung câu hỏi:</h3>
                <div class="text-lg text-gray-900 bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($question->content)) !!}
                </div>
            </div>

            <!-- Question Image -->
            @if($question->image_url)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Hình ảnh:</h3>
                <img src="{{ asset('storage/' . $question->image_url) }}" alt="Question Image" class="max-w-lg rounded-lg shadow-md">
            </div>
            @endif

            <!-- Answers -->
            @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->answers->count() > 0)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Đáp án:</h3>
                <div class="space-y-2">
                    @foreach($question->answers->sortBy('order') as $index => $answer)
                    <div class="flex items-start p-4 rounded-lg border-2 {{ $answer->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $answer->is_correct ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} font-bold mr-3">
                            {{ chr(65 + $index) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 {{ $answer->is_correct ? 'font-semibold' : '' }}">
                                {{ $answer->content }}
                            </p>
                            @if($answer->is_correct)
                            <p class="text-green-600 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Đáp án đúng
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Explanation -->
            @if($question->explanation)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Giải thích:</h3>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <p class="text-gray-900">{{ $question->explanation }}</p>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="border-t pt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Thông tin khác:</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Ngày tạo:</p>
                        <p class="font-medium">{{ $question->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cập nhật lần cuối:</p>
                        <p class="font-medium">{{ $question->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Trong ngân hàng câu hỏi:</p>
                        <p class="font-medium">{{ $question->in_question_bank ? 'Có' : 'Không' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Liên kết với đề thi:</p>
                        <p class="font-medium">{{ $question->exam_id ? 'Có' : 'Chưa' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Button -->
    <div class="mt-6">
        <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này? Hành động này không thể hoàn tác!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                Xóa Câu hỏi
            </button>
        </form>
    </div>
</div>
@endsection
