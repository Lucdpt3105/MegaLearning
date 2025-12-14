@extends('admin.layout')

@section('title', 'Chi tiết câu hỏi')
@section('page-title', 'Chi tiết câu hỏi')
@section('page-description', 'Xem thông tin chi tiết câu hỏi')

@section('content')

<!-- Header Actions -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Thông tin câu hỏi</h3>
        <p class="text-sm text-gray-600">ID: {{ $question->id }}</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.questions.edit', $question) }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
            <i data-feather="edit-2" class="w-5 h-5"></i>
            Chỉnh sửa
        </a>
        
        <a href="{{ route('admin.questions.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>
</div>

<!-- Question Details -->
<div class="grid grid-cols-3 gap-6 mb-6">
    <div class="col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Nội dung câu hỏi</h3>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="text-base text-gray-900 leading-relaxed">
                    {!! nl2br(e($question->content)) !!}
                </div>
            </div>

            <!-- Answers -->
            @if($question->type === 'multiple_choice' || $question->type === 'true_false')
                <h4 class="text-md font-semibold text-gray-900 mb-3">Đáp án</h4>
                <div class="space-y-2">
                    @foreach($question->answers->sortBy('order') as $key => $answer)
                        <div class="flex items-start gap-3 p-3 rounded-lg
                            {{ $answer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-semibold
                                {{ $answer->is_correct ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }}">
                                {{ chr(65 + $key) }}
                            </span>
                            <span class="flex-1 text-sm {{ $answer->is_correct ? 'font-medium text-green-900' : 'text-gray-700' }}">
                                {{ $answer->content }}
                            </span>
                            @if($answer->is_correct)
                                <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($question->type === 'essay')
                <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-blue-900">
                        <i data-feather="edit-3" class="w-4 h-4 inline"></i>
                        Câu hỏi tự luận - Học sinh sẽ nhập câu trả lời
                    </p>
                </div>
            @endif

            <!-- Explanation -->
            @if($question->explanation)
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">Giải thích</h4>
                    <div class="p-4 bg-indigo-50 rounded-lg border-l-4 border-indigo-500">
                        <p class="text-sm text-indigo-900">{{ $question->explanation }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-span-1">
        <!-- Meta Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="text-sm text-gray-600 mb-1">Môn học</div>
                    <div class="text-base font-medium text-gray-900">
                        {{ $question->subject ? $question->subject->name : 'N/A' }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-600 mb-1">Loại câu hỏi</div>
                    <div>
                        <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                            {{ ucfirst($question->type) }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-600 mb-1">Độ khó</div>
                    <div>
                        <span class="px-3 py-1 text-sm rounded-full 
                            {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-600 mb-1">Người tạo</div>
                    <div class="text-base font-medium text-gray-900">
                        {{ $question->creator ? $question->creator->name : 'N/A' }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-600 mb-1">Ngày tạo</div>
                    <div class="text-base text-gray-900">
                        {{ $question->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                @if($question->updated_at != $question->created_at)
                <div>
                    <div class="text-sm text-gray-600 mb-1">Cập nhật</div>
                    <div class="text-base text-gray-900">
                        {{ $question->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Used in Exams -->
        @if($question->exams->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Được sử dụng trong</h3>
            <div class="space-y-2">
                @foreach($question->exams as $exam)
                    <a href="{{ route('admin.exams.show', $exam) }}" 
                       class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                        <div class="text-xs text-gray-600">{{ $exam->subject ? $exam->subject->name : 'N/A' }}</div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Button -->
<div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
    <h3 class="text-lg font-semibold text-red-900 mb-2">Xóa câu hỏi</h3>
    <p class="text-sm text-gray-600 mb-4">Xóa câu hỏi này khỏi hệ thống. Hành động này không thể hoàn tác.</p>
    
    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" 
          onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này? Hành động này không thể hoàn tác!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
            <i data-feather="trash-2" class="w-4 h-4 inline"></i>
            Xóa câu hỏi
        </button>
    </form>
</div>

@endsection
