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
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    @if($submission->grading_status === 'graded')
    <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
        <strong>ℹ️ Thông báo:</strong> Bài thi này đã được chấm điểm và không thể chấm lại.
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Questions & Answers -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('teacher.grading.grade', $submission) }}" method="POST" id="gradingForm">
                @csrf

                <!-- Tổng điểm tự động (Trắc nghiệm & Đúng/Sai) -->
                @php
                    $autoScore = 0;
                    $totalAutoPoints = 0;
                    $manualScore = 0;
                    $totalManualPoints = 0;
                    
                    foreach($examQuestions as $question) {
                        $questionId = $question->id;
                        $type = $question->pivot->custom_type ?? $question->type;
                        $points = $question->pivot->points;
                        
                        if (in_array($type, ['multiple_choice', 'true_false'])) {
                            $totalAutoPoints += $points;
                            
                            $studentAnswer = $studentAnswers[$questionId] ?? null;
                            $isCorrect = false;
                            
                            if ($studentAnswer) {
                                if ($type === 'multiple_choice') {
                                    // Lấy đáp án đúng từ bảng answers (is_correct = 1)
                                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                                    
                                    if ($correctAnswerFromDB) {
                                        // Student answer có thể là answer_id hoặc text (A, B, C, D)
                                        if (is_numeric($studentAnswer)) {
                                            // So sánh trực tiếp answer_id
                                            $isCorrect = ((int)$studentAnswer === $correctAnswerFromDB->id);
                                        } else {
                                            // Tìm vị trí của đáp án đúng trong danh sách đã sort
                                            $allAnswersSorted = $question->answers->sortBy('order')->values();
                                            $correctLetter = null;
                                            
                                            foreach ($allAnswersSorted as $index => $ans) {
                                                if ($ans->id === $correctAnswerFromDB->id) {
                                                    $correctLetter = chr(65 + $index); // A=65, B=66, C=67, D=68
                                                    break;
                                                }
                                            }
                                            
                                            if ($correctLetter) {
                                                $isCorrect = (strtoupper(trim($studentAnswer)) === $correctLetter);
                                            }
                                        }
                                    }
                                } elseif ($type === 'true_false') {
                                    // Lấy đáp án đúng từ database
                                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                                    
                                    if ($correctAnswerFromDB) {
                                        // So sánh answer_id (giống multiple_choice)
                                        $isCorrect = ((int)$studentAnswer === $correctAnswerFromDB->id);
                                    }
                                }
                                
                                if ($isCorrect) {
                                    $autoScore += $points;
                                }
                            }
                        } else {
                            $totalManualPoints += $points;
                        }
                    }
                @endphp

                <!-- Questions -->
                @foreach($examQuestions as $index => $question)
                    @php
                        $questionId = $question->id;
                        $type = $question->pivot->custom_type ?? $question->type;
                        $points = $question->pivot->points;
                        $content = $question->pivot->custom_content ?? $question->content;
                        
                        // Lấy đáp án
                        $customAnswers = $question->pivot->custom_answers;
                        if (is_string($customAnswers)) {
                            $customAnswers = json_decode($customAnswers, true);
                        }
                        
                        $studentAnswer = $studentAnswers[$questionId] ?? null;
                        $isCorrect = false;
                        
                        // Xác định đáp án đúng và kiểm tra đúng/sai
                        if ($type === 'multiple_choice') {
                            if ($studentAnswer) {
                                // Lấy đáp án đúng từ bảng answers (is_correct = 1)
                                $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                                
                                if ($correctAnswerFromDB) {
                                    if (is_numeric($studentAnswer)) {
                                        // So sánh trực tiếp answer_id
                                        $isCorrect = ((int)$studentAnswer === $correctAnswerFromDB->id);
                                    } else {
                                        // Tìm vị trí của đáp án đúng trong danh sách đã sort
                                        $allAnswersSorted = $question->answers->sortBy('order')->values();
                                        $correctLetter = null;
                                        
                                        foreach ($allAnswersSorted as $index => $ans) {
                                            if ($ans->id === $correctAnswerFromDB->id) {
                                                $correctLetter = chr(65 + $index);
                                                break;
                                            }
                                        }
                                        
                                        if ($correctLetter) {
                                            $isCorrect = (strtoupper(trim($studentAnswer)) === $correctLetter);
                                        }
                                    }
                                }
                            }
                        } elseif ($type === 'true_false') {
                            // Lấy đáp án đúng từ bảng answers (is_correct = 1)
                            $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                            
                            if ($correctAnswerFromDB && $studentAnswer) {
                                // So sánh answer_id (giống multiple_choice)
                                $isCorrect = ((int)$studentAnswer === $correctAnswerFromDB->id);
                            }
                        } elseif ($type === 'fill_blank') {
                            $correctAnswer = $customAnswers['correct_answer'] ?? $question->correct_answer ?? null;
                            $isCorrect = $studentAnswer && $correctAnswer && 
                                         strtolower(trim($studentAnswer)) === strtolower(trim($correctAnswer));
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
                                </div>
                                <p class="text-gray-900 font-medium">{!! nl2br(e($content)) !!}</p>
                            </div>
                        </div>

                        @if($type === 'multiple_choice')
                            <!-- Multiple Choice -->
                            <div class="space-y-2 mt-4">
                                @php
                                    // Lấy đáp án đúng từ database
                                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                                    $allAnswersSorted = $question->answers->sortBy('order')->values();
                                    
                                    // Xác định letter của đáp án đúng
                                    $correctLetter = null;
                                    if ($correctAnswerFromDB) {
                                        foreach ($allAnswersSorted as $idx => $ans) {
                                            if ($ans->is_correct) {
                                                $correctLetter = chr(65 + $idx);
                                                break;
                                            }
                                        }
                                    }
                                    
                                    // Xác định letter mà student chọn
                                    $studentLetter = null;
                                    if ($studentAnswer) {
                                        if (is_numeric($studentAnswer)) {
                                            foreach ($allAnswersSorted as $idx => $ans) {
                                                if ($ans->id == (int)$studentAnswer) {
                                                    $studentLetter = chr(65 + $idx);
                                                    break;
                                                }
                                            }
                                        } else {
                                            $studentLetter = strtoupper(trim($studentAnswer));
                                        }
                                    }
                                    
                                    // Lấy danh sách options để hiển thị
                                    $customAnswers = $question->pivot->custom_answers;
                                    if (is_string($customAnswers)) {
                                        $customAnswers = json_decode($customAnswers, true);
                                    }
                                    
                                    $options = [];
                                    if ($customAnswers) {
                                        foreach(['A', 'B', 'C', 'D'] as $opt) {
                                            $key = 'option_' . strtolower($opt);
                                            if (isset($customAnswers[$key])) {
                                                $options[$opt] = $customAnswers[$key];
                                            }
                                        }
                                    } else {
                                        foreach ($allAnswersSorted as $idx => $ans) {
                                            $options[chr(65 + $idx)] = $ans->content;
                                        }
                                    }
                                @endphp
                                
                                @foreach($options as $option => $optionText)
                                    @php
                                        $isCorrectOption = ($correctLetter === $option);
                                        $isSelected = ($studentLetter === $option);
                                    @endphp
                                    <div class="flex items-center p-3 rounded-lg {{ $isCorrectOption ? 'bg-green-50 border-2 border-green-400' : ($isSelected ? 'bg-red-50 border-2 border-red-400' : 'bg-gray-50 border border-gray-200') }}">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $isCorrectOption ? 'bg-green-500 text-white' : ($isSelected ? 'bg-red-500 text-white' : 'bg-gray-300 text-gray-700') }}">
                                            {{ $option }}
                                        </span>
                                        <span class="ml-3 flex-1 {{ $isCorrectOption ? 'font-semibold text-green-900' : ($isSelected ? 'text-red-900' : 'text-gray-700') }}">{{ $optionText }}</span>
                                        @if($isSelected && !$isCorrectOption)
                                            <span class="text-red-600 font-semibold">❌ Học sinh chọn</span>
                                        @elseif($isCorrectOption && $isSelected)
                                            <span class="text-green-600 font-semibold">✅ Đúng!</span>
                                        @elseif($isCorrectOption)
                                            <span class="text-green-600 font-semibold">✅ Đáp án đúng</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @elseif($type === 'true_false')
                            <!-- True/False -->
                            <div class="space-y-2 mt-4">
                                @php
                                    // Lấy đáp án đúng từ database (is_correct = 1)
                                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                                    $allAnswers = $question->answers->sortBy('order');
                                @endphp
                                @foreach($allAnswers as $answer)
                                    @php
                                        $isCorrectOption = ($answer->is_correct == 1);
                                        $isSelected = ($studentAnswer && (int)$studentAnswer === $answer->id);
                                    @endphp
                                    <div class="flex items-center p-3 rounded-lg {{ $isCorrectOption ? 'bg-green-50 border-2 border-green-400' : ($isSelected ? 'bg-red-50 border-2 border-red-400' : 'bg-gray-50 border border-gray-200') }}">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $isCorrectOption ? 'bg-green-500 text-white' : ($isSelected ? 'bg-red-500 text-white' : 'bg-gray-300 text-gray-700') }}">
                                            {{ strtolower($answer->content) === 'Đúng' ? '✓' : '✗' }}
                                        </span>
                                        <span class="ml-3 flex-1 {{ $isCorrectOption ? 'font-semibold text-green-900' : ($isSelected ? 'text-red-900' : 'text-gray-700') }}">{{ $answer->content }}</span>
                                        @if($isSelected && !$isCorrectOption)
                                            <span class="text-red-600 font-semibold">❌ Học sinh chọn</span>
                                        @elseif($isCorrectOption && $isSelected)
                                            <span class="text-green-600 font-semibold">✅ Đúng!</span>
                                        @elseif($isCorrectOption)
                                            <span class="text-green-600 font-semibold">✅ Đáp án đúng</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @elseif($type === 'fill_blank')
                            <!-- Fill Blank - Chỉ hiển thị câu trả lời -->
                            <div class="mt-4">
                                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Câu trả lời của học sinh:</label>
                                    <div class="bg-white border border-gray-300 rounded-lg p-3">
                                        @if($studentAnswer)
                                            <p class="text-gray-900 font-medium">{{ is_array($studentAnswer) ? ($studentAnswer['answer'] ?? '') : $studentAnswer }}</p>
                                        @else
                                            <p class="text-gray-400 italic">Học sinh chưa trả lời</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @elseif($type === 'essay')
                            <!-- Essay - Chỉ hiển thị câu trả lời -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Câu trả lời của học sinh:</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-lg p-4 min-h-[100px]">
                                    @if($studentAnswer)
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ is_array($studentAnswer) ? ($studentAnswer['answer'] ?? '') : $studentAnswer }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Học sinh chưa trả lời</p>
                                    @endif
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
                                  placeholder="Nhận xét về bài làm của học sinh..."
                                  {{ $submission->grading_status === 'graded' ? 'readonly' : '' }}>{{ $submission->feedback }}</textarea>
                    </div>

                    <!-- Hiển thị điểm -->
                    @if($totalAutoPoints > 0)
                    <div class="mb-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-green-900 mb-1">✅ Điểm tự động (Trắc nghiệm & Đúng/Sai)</h3>
                                <p class="text-sm text-green-700">Hệ thống đã chấm tự động các câu trắc nghiệm</p>
                            </div>
                            <div class="text-right">
                                <p class="text-4xl font-bold text-green-600">{{ number_format($autoScore, 1) }}</p>
                                <p class="text-sm text-green-700">/ {{ number_format($totalAutoPoints, 1) }} điểm</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tổng điểm (Tối đa: {{ $submission->exam->total_points }} điểm)
                        </label>
                        <input type="number" 
                               name="final_score" 
                               min="{{ $autoScore }}" 
                               max="{{ $submission->exam->total_points }}" 
                               step="0.5"
                               value="{{ $submission->score }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-2xl font-bold"
                               placeholder="Tổng điểm..."
                               {{ $submission->grading_status === 'graded' ? 'readonly' : '' }}>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($submission->grading_status === 'graded')
                                Bài thi đã được chấm, không thể thay đổi điểm
                            @else
                                Điểm tối thiểu: {{ number_format($autoScore, 1) }} (từ câu trắc nghiệm & đúng/sai)
                            @endif
                        </p>
                    </div>

                    <div class="flex gap-3">
                        @if($submission->grading_status !== 'graded')
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                ✅ Lưu điểm & Hoàn thành
                            </button>
                        @else
                            <button type="button" disabled class="px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                                ✅ Đã chấm xong
                            </button>
                        @endif
                        <a href="{{ route('teacher.grading.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                            {{ $submission->grading_status === 'graded' ? 'Quay lại' : 'Hủy' }}
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
                        <p class="font-medium text-gray-900">{{ gmdate('i:s', $submission->time_spent) }}</p>
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
