@extends('layouts.app')

@section('title', 'Ngân hàng Câu hỏi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ngân hàng Câu hỏi</h1>
        <p class="text-gray-600 mt-2">Quản lý câu hỏi theo từng môn học</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Subject Selection Grid -->
    @if($subjects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($subjects as $subject)
        <a href="{{ route('teacher.questions.by-subject', $subject) }}" class="block">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <!-- Card Header -->
                <div class="bg-gradient-to-r {{ $subject->status === 'active' ? 'from-indigo-500 to-purple-500' : 'from-gray-400 to-gray-500' }} p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-4xl">📚</span>
                        @php
                            $questionCount = $subject->questions()->where('in_question_bank', true)->count();
                        @endphp
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            {{ $questionCount }} câu hỏi
                        </span>
                    </div>
                    <h3 class="text-xl font-bold">{{ $subject->name }}</h3>
                    <p class="text-sm mt-1 opacity-90">{{ $subject->code }}</p>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <!-- Statistics -->
                    <div class="space-y-3">
                        @php
                            $stats = [
                                'remember' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'remember')->count(),
                                'understand' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'understand')->count(),
                                'apply' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'apply')->count(),
                                'analyze' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'analyze')->count(),
                            ];
                        @endphp
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">🟢 Nhận biết:</span>
                            <span class="font-semibold text-green-600">{{ $stats['remember'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">🔵 Thông hiểu:</span>
                            <span class="font-semibold text-blue-600">{{ $stats['understand'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">🟡 Vận dụng:</span>
                            <span class="font-semibold text-yellow-600">{{ $stats['apply'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">🔴 VD Cao:</span>
                            <span class="font-semibold text-red-600">{{ $stats['analyze'] }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-6 pt-4 border-t">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Xem chi tiết →</span>
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có môn học nào</h3>
        <p class="text-gray-600 mb-6">Vui lòng tạo môn học trước khi thêm câu hỏi</p>
        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Môn học
        </a>
    </div>
    @endif

    <!-- Overall Statistics -->
    @if($subjects->count() > 0)
    <div class="mt-8 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Tổng quan Ngân hàng Câu hỏi</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $totalQuestions = \App\Models\Question::where('in_question_bank', true)
                    ->where('created_by', Auth::id())
                    ->count();
                $multipleChoice = \App\Models\Question::where('in_question_bank', true)
                    ->where('created_by', Auth::id())
                    ->where('type', 'multiple_choice')
                    ->count();
                $trueFalse = \App\Models\Question::where('in_question_bank', true)
                    ->where('created_by', Auth::id())
                    ->where('type', 'true_false')
                    ->count();
                $essay = \App\Models\Question::where('in_question_bank', true)
                    ->where('created_by', Auth::id())
                    ->where('type', 'essay')
                    ->count();
            @endphp
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-indigo-600">{{ $totalQuestions }}</div>
                <div class="text-sm text-gray-600 mt-1">Tổng câu hỏi</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $multipleChoice }}</div>
                <div class="text-sm text-gray-600 mt-1">Trắc nghiệm</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-green-600">{{ $trueFalse }}</div>
                <div class="text-sm text-gray-600 mt-1">Đúng/Sai</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $essay }}</div>
                <div class="text-sm text-gray-600 mt-1">Tự luận</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
