@extends('layouts.app')

@section('title', 'Chấm bài thi - ' . $submission->student->name)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('teacher.grading.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Chấm bài thi</h1>
                <p class="text-gray-600 mt-1">{{ $submission->exam->title }} - {{ $submission->student->name }}</p>
            </div>
            @if($submission->grading_status === 'pending')
                <form action="{{ route('teacher.grading.auto-grade', $submission) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        🤖 Chấm tự động
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Questions & Answers -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('teacher.grading.grade', $submission) }}" method="POST" id="gradingForm">
                @csrf

                <!-- Auto-graded Score (if any) -->
                @if($autoScore > 0)
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                    <h3 class="font-semibold text-blue-900 mb-2">📊 Điểm tự động (Trắc nghiệm)</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ number_format($autoScore, 1) }}<span class="text-xl text-blue-400">/{{ $totalAutoPoints }}</span>
                    </p>
                </div>
                @endif

                <!-- Questions -->
                @foreach($examQuestions as $index => $question)
                    @php
                        $questionId = $question->id;
                        $type = $question->type;
                        $points = $question->pivot->points;
                        $content = $question->content;
                        
                        // Get correct answer from question's answers relationship
                        $correctAnswerObj = $question->answers->where('is_correct', true)->first();
                        $correctAnswerId = $correctAnswerObj ? $correctAnswerObj->id : null;
                        
                        $studentAnswerId = $studentAnswers[$questionId] ?? null;
                        $isCorrect = false;
                        
                        if ($type === 'multiple_choice') {
                            $isCorrect = $correctAnswerId && $studentAnswerId && (string)$studentAnswerId === (string)$correctAnswerId;
                        }
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm p-6 {{ $isCorrect ? 'border-2 border-green-300' : ($type === 'essay' ? 'border-2 border-yellow-300' : 'border-2 border-red-300') }}">
                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                                        Câu {{ $index + 1 }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                        {{ $type === 'multiple_choice' ? '📝 Trắc nghiệm' : ($type === 'true_false' ? '✔️ Đúng/Sai' : '📄 Tự luận') }}
                                    </span>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                                        {{ $points }} điểm
                                    </span>
                                    @if($type === 'multiple_choice')
                                        @if($isCorrect)
                                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold">✅ Đúng</span>
                                        @elseif($studentAnswerId)
                                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">❌ Sai</span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-400 text-white rounded-full text-sm font-semibold">⊘ Chưa trả lời</span>
                                        @endif
                                    @endif
                                </div>
                                <p class="text-gray-900 font-medium">{!! nl2br(e($content)) !!}</p>
                            </div>
                        </div>

                        @if($type === 'multiple_choice')
                            <!-- Multiple Choice -->
                            <div class="space-y-2 mt-4">
                                @foreach($question->answers as $answer)
                                    @php
                                        $isSelected = $studentAnswerId && (string)$studentAnswerId === (string)$answer->id;
                                        $isCorrectOption = $answer->is_correct;
                                    @endphp
                                    <div class="flex items-center p-3 rounded-lg {{ $isCorrectOption ? 'bg-green-50 border-2 border-green-300' : ($isSelected ? 'bg-red-50 border-2 border-red-300' : 'bg-gray-50 border border-gray-200') }}">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $isCorrectOption ? 'bg-green-500 text-white' : ($isSelected ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700') }}">
                                            {{ chr(65 + $loop->index) }}
                                        </span>
                                        <span class="ml-3 flex-1">{{ $answer->content }}</span>
                                        @if($isSelected && !$isCorrectOption)
                                            <span class="text-red-600 font-semibold text-sm">❌ Học sinh đã chọn</span>
                                        @elseif($isSelected && $isCorrectOption)
                                            <span class="text-green-600 font-semibold text-sm">✅ Học sinh chọn đúng</span>
                                        @elseif($isCorrectOption)
                                            <span class="text-green-600 font-semibold text-sm">✅ Đáp án đúng</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @elseif($type === 'true_false')
                            <!-- True/False -->
                            <div class="space-y-2 mt-4">
                                @foreach(['true' => 'Đúng', 'false' => 'Sai'] as $value => $label)
                                    @php
                                        $isSelected = $studentAnswer === $value;
                                        $isCorrectOption = $correctAnswer === $value;
                                    @endphp
                                    <div class="flex items-center p-3 rounded-lg {{ $isCorrectOption ? 'bg-green-50 border-2 border-green-300' : ($isSelected ? 'bg-red-50 border-2 border-red-300' : 'bg-gray-50 border border-gray-200') }}">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $isCorrectOption ? 'bg-green-500 text-white' : ($isSelected ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700') }}">
                                            {{ $value === 'true' ? '✓' : '✗' }}
                                        </span>
                                        <span class="ml-3 flex-1">{{ $label }}</span>
                                        @if($isSelected && !$isCorrectOption)
                                            <span class="text-red-600 font-semibold">❌ Học sinh chọn</span>
                                        @elseif($isCorrectOption)
                                            <span class="text-green-600 font-semibold">✅ Đáp án đúng</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @elseif($type === 'essay')
                            <!-- Essay Answer -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Câu trả lời của học sinh:</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-lg p-4 min-h-[100px]">
                                    @if($studentAnswer)
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ is_array($studentAnswer) ? ($studentAnswer['answer'] ?? '') : $studentAnswer }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Học sinh chưa trả lời</p>
                                    @endif
                                </div>

                                <!-- Manual Grading Input -->
                                <div class="mt-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        📝 Chấm điểm thủ công (Tối đa: {{ $points }} điểm)
                                    </label>
                                    <input type="number" 
                                           name="essay_scores[{{ $questionId }}]" 
                                           min="0" 
                                           max="{{ $points }}" 
                                           step="0.5"
                                           value="{{ is_array($studentAnswer) ? ($studentAnswer['score'] ?? 0) : 0 }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"
                                           placeholder="Nhập điểm...">
                                </div>

                                @if($question->explanation)
                                <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm font-medium text-blue-900 mb-1">💡 Gợi ý đáp án:</p>
                                    <p class="text-sm text-blue-800">{{ $question->explanation }}</p>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Feedback & Final Score -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Nhận xét chung</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhận xét của giáo viên</label>
                        <textarea name="feedback" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Nhận xét về bài làm của học sinh...">{{ $submission->feedback }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tổng điểm (Tối đa: {{ $submission->exam->total_points }} điểm)
                        </label>
                        <input type="number" 
                               name="final_score" 
                               min="0" 
                               max="{{ $submission->exam->total_points }}" 
                               step="0.1"
                               value="{{ $submission->score ?? $autoScore }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-2xl font-bold"
                               placeholder="Nhập điểm từ 0 đến {{ $submission->exam->total_points }}..."
                               required>
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                💡 <strong>Điểm tự động:</strong> {{ number_format($autoScore, 1) }}/{{ $totalAutoPoints }} (trắc nghiệm)
                            </p>
                            <p class="text-xs text-blue-600 mt-1">Giáo viên có thể nhập bất kỳ điểm nào từ 0 đến {{ $submission->exam->total_points }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            ✅ Lưu điểm & Hoàn thành
                        </button>
                        <a href="{{ route('teacher.grading.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right: Submission Info -->
        <div class="space-y-6">
            <!-- Student Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">👤 Thông tin học sinh</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Họ tên</p>
                        <p class="font-medium text-gray-900">{{ $submission->student->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $submission->student->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Exam Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">📋 Thông tin bài thi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Đề thi</p>
                        <p class="font-medium text-gray-900">{{ $submission->exam->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Thời gian nộp</p>
                        <p class="font-medium text-gray-900">{{ $submission->submitted_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Thời gian làm</p>
                        <p class="font-medium text-gray-900">{{ $submission->time_spent }} phút</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tổng điểm</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $submission->exam->total_points }}</p>
                    </div>
                </div>
            </div>

            <!-- Grading Status -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">📊 Trạng thái chấm</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Trạng thái</p>
                        @if($submission->grading_status === 'pending')
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                ⏳ Chờ chấm
                            </span>
                        @elseif($submission->grading_status === 'graded')
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                ✅ Đã chấm
                            </span>
                        @endif
                    </div>
                    @if($submission->graded_at)
                    <div>
                        <p class="text-sm text-gray-600">Chấm lúc</p>
                        <p class="font-medium text-gray-900">{{ $submission->graded_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($submission->grader)
                    <div>
                        <p class="text-sm text-gray-600">Người chấm</p>
                        <p class="font-medium text-gray-900">{{ $submission->grader->name }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
