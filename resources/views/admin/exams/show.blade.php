@extends('admin.layout')

@section('title', 'Chi tiết đề thi')
@section('page-title', $exam->title)
@section('page-description', $exam->subject->name ?? 'N/A')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<!-- Header Actions -->
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="px-3 py-1 text-sm rounded-full 
            {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
            {{ $exam->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
            {{ $exam->status === 'archived' ? 'bg-orange-100 text-orange-800' : '' }}">
            {{ $exam->status === 'published' ? 'Hoạt động' : '' }}
            {{ $exam->status === 'draft' ? 'Nháp' : '' }}
            {{ $exam->status === 'archived' ? 'Lưu trữ' : '' }}
        </span>
        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
            {{ ucfirst($exam->type) }}
        </span>
        @if($exam->classRoom)
        <span class="text-sm text-gray-600">
            {{ $exam->classRoom->name }}
        </span>
        @endif
    </div>

    <div class="flex items-center gap-3">
        @if($exam->status === 'draft')
        <form action="{{ route('admin.exams.publish', $exam) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i data-feather="check-circle" class="w-5 h-5"></i>
                Xuất bản
            </button>
        </form>
        @endif

        <a href="{{ route('admin.exams.edit', $exam) }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i data-feather="edit-2" class="w-5 h-5"></i>
            Chỉnh sửa
        </a>

        <a href="{{ route('admin.exams.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Câu hỏi</div>
        <div class="text-3xl font-bold text-gray-900">{{ $exam->questions->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Tổng điểm</div>
        <div class="text-3xl font-bold text-gray-900">{{ $exam->total_points }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Thời gian</div>
        <div class="text-3xl font-bold text-gray-900">{{ $exam->duration }} <span class="text-lg text-gray-600">phút</span></div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Điểm đạt</div>
        <div class="text-3xl font-bold text-gray-900">{{ $exam->passing_score }}</div>
    </div>
</div>

<!-- Exam Info -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin đề thi</h3>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-gray-600">Giáo viên tạo</p>
            <p class="text-base font-medium text-gray-900">{{ $exam->creator->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Môn học</p>
            <p class="text-base font-medium text-gray-900">{{ $exam->subject->name ?? 'N/A' }}</p>
        </div>
        @if($exam->start_time && $exam->end_time)
        <div>
            <p class="text-sm text-gray-600">Thời gian bắt đầu</p>
            <p class="text-base font-medium text-gray-900">{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Thời gian kết thúc</p>
            <p class="text-base font-medium text-gray-900">{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}</p>
        </div>
        @endif
        @if($exam->description)
        <div class="col-span-2">
            <p class="text-sm text-gray-600">Mô tả</p>
            <p class="text-base text-gray-900 mt-1">{{ $exam->description }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Questions List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh sách câu hỏi ({{ $exam->questions->count() }} câu)</h3>
    
    @if($exam->questions->isEmpty())
        <div class="text-center py-12">
            <i data-feather="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có câu hỏi</h3>
            <p class="text-gray-600 mb-4">Đề thi này chưa có câu hỏi nào</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($exam->questions->sortBy('pivot.order') as $index => $question)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">
                                    {{ ucfirst($question->type) }}
                                </span>
                                @if($question->topic)
                                <span class="text-sm text-gray-600">
                                    {{ $question->topic->name }}
                                </span>
                                @endif
                                <span class="text-sm font-semibold text-blue-600">
                                    {{ $question->pivot->points }} điểm
                                </span>
                            </div>
                            <div class="text-gray-900 mb-2">{!! nl2br(e($question->content)) !!}</div>
                            
                            @if($question->type === 'multiple_choice')
                                <div class="space-y-1 mt-3">
                                    @foreach($question->answers->sortBy('order') as $key => $answer)
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center
                                                {{ $answer->is_correct ? 'border-green-500 bg-green-50 text-green-700 font-semibold' : 'border-gray-300' }}">
                                                {{ chr(65 + $key) }}
                                            </span>
                                            <span class="{{ $answer->is_correct ? 'font-medium text-green-700' : 'text-gray-700' }}">
                                                {{ $answer->content }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($question->explanation)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <p class="text-sm text-gray-700"><strong>Giải thích:</strong> {{ $question->explanation }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
