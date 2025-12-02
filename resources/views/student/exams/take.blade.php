@extends('layouts.exam')

@section('title', 'Làm Bài: ' . $exam->title)

@section('content')
<!-- Timer and Header - Fixed to top -->
<div class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 max-w-5xl">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                <p class="text-gray-600">{{ $exam->subject->name }} - {{ $exam->classRoom->name }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 mb-1">Thời gian còn lại</div>
                <div id="timer" class="text-3xl font-bold text-red-600">{{ gmdate('i:s', $timeRemaining) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
        @csrf
        
        <!-- Questions -->
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-gray-900 mb-2">
                                        {{ $question->pivot->custom_content ?? $question->content }}
                                    </p>
                                    @if($question->difficulty)
                                        <span class="inline-block px-2 py-1 text-xs rounded
                                            {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $question->difficulty === 'easy' ? 'Dễ' : ($question->difficulty === 'medium' ? 'Trung bình' : 'Khó') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-4 text-sm text-gray-500">
                                    {{ $question->pivot->points ?? 1 }} điểm
                                </div>
                            </div>

                            <!-- Multiple Choice Answers -->
                            @if($question->type === 'multiple_choice')
                                <div class="space-y-3">
                                    @foreach($question->shuffled_answers as $answer)
                                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $answer->id }}"
                                                   class="mt-1 mr-3 w-4 h-4 text-blue-600"
                                                   {{ isset($submission->answers[$question->id]) && $submission->answers[$question->id] == $answer->id ? 'checked' : '' }}>
                                            <span class="flex-1 text-gray-700">{{ $answer->content }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Essay Question -->
                            @if($question->type === 'essay')
                                <textarea name="answers[{{ $question->id }}]" 
                                          rows="6" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Nhập câu trả lời của bạn...">{{ $submission->answers[$question->id] ?? '' }}</textarea>
                            @endif

                            <!-- Fill in the Blank -->
                            @if($question->type === 'fill_blank')
                                <input type="text" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $submission->answers[$question->id] ?? '' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Nhập câu trả lời...">
                            @endif

                            <!-- True/False -->
                            @if($question->type === 'true_false')
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="true"
                                               class="mr-3 w-4 h-4 text-blue-600"
                                               {{ isset($submission->answers[$question->id]) && $submission->answers[$question->id] == 'true' ? 'checked' : '' }}>
                                        <span class="text-gray-700">Đúng</span>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="false"
                                               class="mr-3 w-4 h-4 text-blue-600"
                                               {{ isset($submission->answers[$question->id]) && $submission->answers[$question->id] == 'false' ? 'checked' : '' }}>
                                        <span class="text-gray-700">Sai</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <div class="flex gap-4">
                <button type="button" 
                        onclick="saveDraft()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    💾 Lưu Nháp
                </button>
                <button type="submit" 
                        onclick="return confirm('Bạn có chắc chắn muốn nộp bài? Không thể chỉnh sửa sau khi nộp.')"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    📤 Nộp Bài
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let timeRemaining = parseInt({{ $timeRemaining }});
const timerElement = document.getElementById('timer');
const examForm = document.getElementById('examForm');

// Update timer display
function updateTimer() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    // Change color when time is running out
    if (timeRemaining <= 300) { // 5 minutes
        timerElement.classList.remove('text-red-600');
        timerElement.classList.add('text-orange-600');
    }
    if (timeRemaining <= 60) { // 1 minute
        timerElement.classList.remove('text-orange-600');
        timerElement.classList.add('text-red-600', 'animate-pulse');
    }
}

// Initial display
updateTimer();

// Countdown timer
const countdown = setInterval(() => {
    timeRemaining--;
    updateTimer();
    
    if (timeRemaining <= 0) {
        clearInterval(countdown);
        alert('Hết giờ làm bài! Bài làm sẽ được tự động nộp.');
        examForm.submit();
    }
}, 1000);

// Save draft function
function saveDraft() {
    const formData = new FormData(examForm);
    // Here you could implement AJAX to save draft without submitting
    alert('Câu trả lời đã được lưu tạm thời trên trình duyệt');
}

// Auto-save to localStorage every 30 seconds
setInterval(() => {
    const formData = new FormData(examForm);
    const answers = {};
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('answers[')) {
            answers[key] = value;
        }
    }
    localStorage.setItem('exam_{{ $exam->id }}_draft', JSON.stringify(answers));
}, 30000);

// Warn before leaving page
window.addEventListener('beforeunload', (e) => {
    e.preventDefault();
    e.returnValue = 'Bạn có chắc chắn muốn rời khỏi trang? Bài làm có thể bị mất.';
});

// Remove warning after form submission
examForm.addEventListener('submit', () => {
    window.removeEventListener('beforeunload', () => {});
});
</script>
@endsection
