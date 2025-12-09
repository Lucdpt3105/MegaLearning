@extends('layouts.app')

@section('title', 'Ngân hàng Câu hỏi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Header with Background Image -->
    <div class="relative overflow-hidden bg-white shadow-sm mb-8">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?w=1600&q=80" 
                 alt="Questions" 
                 class="w-full h-full object-cover opacity-10">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Ngân hàng Câu hỏi
                            </h1>
                            <p class="text-gray-600 mt-1">Quản lý câu hỏi theo từng môn học</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-12">

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="ml-4 text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="ml-4 text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Subject Cards Grid -->
    @if($subjects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($subjects as $index => $subject)
        <a href="{{ route('teacher.questions.by-subject', $subject) }}" class="group block">
            <div class="bg-white rounded-3xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                <!-- Card Header with Image -->
                <div class="relative h-40 overflow-hidden">
                    @php
                        $subjectImages = [
                            'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800&q=80', // Math
                            'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&q=80', // Physics
                            'https://images.unsplash.com/photo-1532619675605-1ede6c2ed2b0?w=800&q=80', // Chemistry
                            'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=800&q=80', // Computer
                            'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800&q=80'  // Literature
                        ];
                        $randomImage = $subjectImages[$index % count($subjectImages)];
                        $questionCount = $subject->questions()->where('in_question_bank', true)->count();
                    @endphp
                    <img src="{{ $randomImage }}" alt="{{ $subject->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                    
                    <!-- Subject Info on Image -->
                    <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <!-- <span class="text-4xl">📚</span> -->
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-sm font-bold text-indigo-600 shadow-lg">
                                {{ $questionCount }} câu hỏi
                            </span>
                        </div>
                        <h3 class="text-xl font-bold drop-shadow-lg">{{ $subject->name }}</h3>
                        <p class="text-sm opacity-90 font-medium">{{ $subject->code }}</p>
                    </div>
                    
                    <!-- Status Badge -->
                    @if($subject->status !== 'active')
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1.5 bg-gray-500/90 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                            Không hoạt động
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <!-- Statistics -->
                    <div class="space-y-2.5">
                        @php
                            $stats = [
                                'remember' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'remember')->count(),
                                'understand' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'understand')->count(),
                                'apply' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'apply')->count(),
                                'analyze' => $subject->questions()->where('in_question_bank', true)->where('bloom_level', 'analyze')->count(),
                            ];
                        @endphp
                        
                        <div class="flex items-center justify-between p-2 rounded-lg bg-green-50 group-hover:bg-green-100 transition-colors">
                            <span class="text-sm font-medium text-gray-700">🟢 Nhận biết</span>
                            <span class="font-bold text-green-600 text-lg">{{ $stats['remember'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50 group-hover:bg-blue-100 transition-colors">
                            <span class="text-sm font-medium text-gray-700">🔵 Thông hiểu</span>
                            <span class="font-bold text-blue-600 text-lg">{{ $stats['understand'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-yellow-50 group-hover:bg-yellow-100 transition-colors">
                            <span class="text-sm font-medium text-gray-700">🟡 Vận dụng</span>
                            <span class="font-bold text-yellow-600 text-lg">{{ $stats['apply'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-red-50 group-hover:bg-red-100 transition-colors">
                            <span class="text-sm font-medium text-gray-700">🔴 VD Cao</span>
                            <span class="font-bold text-red-600 text-lg">{{ $stats['analyze'] }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <div class="flex items-center justify-between text-sm px-3 py-2 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 group-hover:from-indigo-100 group-hover:to-purple-100 transition-colors">
                            <span class="font-semibold text-indigo-700">Xem chi tiết</span>
                            <svg class="w-5 h-5 text-indigo-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="relative bg-white rounded-3xl shadow-xl p-16 text-center overflow-hidden mb-8">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&q=80" 
                 alt="Empty" 
                 class="w-full h-full object-cover opacity-5">
        </div>
        <div class="relative z-10">
            <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-3">Chưa có môn học nào</h3>
            <p class="text-gray-600 mb-8 text-lg max-w-md mx-auto">
                Vui lòng tạo môn học trước khi thêm câu hỏi vào ngân hàng
            </p>
            <a href="{{ route('teacher.subjects.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tạo môn học đầu tiên
            </a>
        </div>
    </div>
    @endif

    <!-- Overall Statistics -->
    @if($subjects->count() > 0)
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 p-8">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Tổng quan Ngân hàng Câu hỏi</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
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
            
            <div class="group relative bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-indigo-200 hover:border-indigo-400">
                <div class="absolute top-3 right-3 text-4xl opacity-20 group-hover:scale-110 transition-transform">📊</div>
                <div class="relative">
                    <div class="text-4xl font-black text-indigo-600 mb-2">{{ $totalQuestions }}</div>
                    <div class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Tổng câu hỏi</div>
                </div>
            </div>
            
            <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-blue-200 hover:border-blue-400">
                <div class="absolute top-3 right-3 text-4xl opacity-20 group-hover:scale-110 transition-transform">📝</div>
                <div class="relative">
                    <div class="text-4xl font-black text-blue-600 mb-2">{{ $multipleChoice }}</div>
                    <div class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Trắc nghiệm</div>
                </div>
            </div>
            
            <div class="group relative bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-green-200 hover:border-green-400">
                <div class="absolute top-3 right-3 text-4xl opacity-20 group-hover:scale-110 transition-transform">✓</div>
                <div class="relative">
                    <div class="text-4xl font-black text-green-600 mb-2">{{ $trueFalse }}</div>
                    <div class="text-sm font-semibold text-green-700 uppercase tracking-wide">Đúng/Sai</div>
                </div>
            </div>
            
            <div class="group relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-purple-200 hover:border-purple-400">
                <div class="absolute top-3 right-3 text-4xl opacity-20 group-hover:scale-110 transition-transform">📄</div>
                <div class="relative">
                    <div class="text-4xl font-black text-purple-600 mb-2">{{ $essay }}</div>
                    <div class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Tự luận</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
@endsection
