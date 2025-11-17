@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.exams.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="px-3 py-1 text-sm rounded-full 
                                {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $exam->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $exam->status === 'archived' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ ucfirst($exam->status) }}
                            </span>
                            <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                {{ ucfirst($exam->type) }}
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ $exam->subject->name }} 
                                @if($exam->classRoom)
                                    - {{ $exam->classRoom->name }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($exam->status === 'draft')
                        <form action="{{ route('teacher.exams.publish', $exam) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Xuất bản
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('teacher.exams.edit', $exam) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Chỉnh sửa
                    </a>

                    <button onclick="toggleNotificationModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Gửi thông báo
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-600 mb-1">Câu hỏi</div>
                <div class="text-3xl font-bold text-gray-900">{{ $exam->questions->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-600 mb-1">Tổng điểm</div>
                <div class="text-3xl font-bold text-gray-900">{{ $exam->total_points }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-600 mb-1">Thời gian</div>
                <div class="text-3xl font-bold text-gray-900">{{ $exam->duration }} <span class="text-lg text-gray-600">phút</span></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-gray-600 mb-1">Điểm đạt</div>
                <div class="text-3xl font-bold text-gray-900">{{ $exam->passing_score }}</div>
            </div>
        </div>

        <!-- Question Management -->
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="switchTab('questions')" id="tab-questions"
                            class="tab-button active px-6 py-4 text-sm font-medium border-b-2">
                        Câu hỏi trong đề thi
                    </button>
                    <button onclick="switchTab('add-from-bank')" id="tab-add-from-bank"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2">
                        Thêm từ ngân hàng
                    </button>
                    <button onclick="switchTab('create-custom')" id="tab-create-custom"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2">
                        Tạo câu hỏi mới
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Questions List -->
            <div id="content-questions" class="tab-content p-6">
                @if($exam->questions->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có câu hỏi</h3>
                        <p class="text-gray-600 mb-4">Hãy thêm câu hỏi từ ngân hàng hoặc tạo câu hỏi mới</p>
                    </div>
                @else
                    <div class="space-y-4" id="questions-list">
                        @foreach($exam->questions->sortBy('pivot.order') as $index => $question)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors" 
                                 data-question-id="{{ $question->id }}">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-2">
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
                                                    <div class="space-y-1">
                                                        @foreach($question->answers->sortBy('order') as $key => $answer)
                                                            <div class="flex items-center gap-2 text-sm">
                                                                <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center
                                                                    {{ $answer->is_correct ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300' }}">
                                                                    {{ chr(65 + $key) }}
                                                                </span>
                                                                <span class="{{ $answer->is_correct ? 'font-medium text-green-700' : 'text-gray-700' }}">
                                                                    {{ $answer->content }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <form action="{{ route('teacher.exams.questions.remove', [$exam, $question->id]) }}" method="POST" 
                                                  onsubmit="return confirm('Xóa câu hỏi này khỏi đề thi?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tab Content: Add from Question Bank -->
            <div id="content-add-from-bank" class="tab-content hidden p-6">
                <form action="{{ route('teacher.exams.questions.add', $exam) }}" method="POST">
                    @csrf
                    
                    @if($availableQuestions->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-600">Không còn câu hỏi nào trong ngân hàng cho môn này</p>
                        </div>
                    @else
                        <div class="space-y-4 mb-6">
                            @foreach($availableQuestions as $question)
                                <label class="flex items-start gap-4 border border-gray-200 rounded-lg p-4 hover:border-blue-300 cursor-pointer">
                                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" 
                                           class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                           onchange="togglePointsInput(this, {{ $question->id }})">
                                    
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
                                            <span class="px-2 py-1 text-xs rounded 
                                                {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($question->difficulty) }}
                                            </span>
                                        </div>
                                        <div class="text-gray-900 mb-2">{!! nl2br(e($question->content)) !!}</div>
                                        
                                        <div id="points-input-{{ $question->id }}" class="hidden mt-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Điểm cho câu này:</label>
                                            <input type="number" name="points[]" step="0.1" min="0" value="1"
                                                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Thêm câu hỏi đã chọn
                        </button>
                    @endif
                </form>
            </div>

            <!-- Tab Content: Create Custom Question -->
            <div id="content-create-custom" class="tab-content hidden p-6">
                <form action="{{ route('teacher.exams.questions.create', $exam) }}" method="POST" id="custom-question-form">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Loại câu hỏi</label>
                            <select name="type" id="custom-type" onchange="toggleAnswerFields()"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="multiple_choice">Trắc nghiệm</option>
                                <option value="essay">Tự luận</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung câu hỏi</label>
                            <textarea name="content" required rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="Nhập nội dung câu hỏi..."></textarea>
                        </div>

                        <div id="answers-section">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Đáp án</label>
                            <div id="answers-list" class="space-y-2 mb-2">
                                <!-- Answers will be added here dynamically -->
                            </div>
                            <button type="button" onclick="addAnswer()" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                + Thêm đáp án
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giải thích (không bắt buộc)</label>
                            <textarea name="explanation" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="Giải thích đáp án đúng..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Điểm</label>
                            <input type="number" name="points" step="0.1" min="0" value="1" required
                                   class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Thêm câu hỏi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notification-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-semibold mb-4">Gửi thông báo</h3>
        <form action="{{ route('teacher.exams.notify', $exam) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung thông báo</label>
                <textarea name="message" required rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="VD: Đề thi giữa kỳ đã được xuất bản. Hạn nộp bài: 20/12/2024"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleNotificationModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Hủy
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Gửi thông báo
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
// Tab switching
function switchTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeButton.classList.remove('border-transparent', 'text-gray-600');
}

// Toggle points input
function togglePointsInput(checkbox, questionId) {
    const pointsInput = document.getElementById('points-input-' + questionId);
    if (checkbox.checked) {
        pointsInput.classList.remove('hidden');
    } else {
        pointsInput.classList.add('hidden');
    }
}

// Notification modal
function toggleNotificationModal() {
    document.getElementById('notification-modal').classList.toggle('hidden');
}

// Custom question answer management
let answerCount = 0;

function toggleAnswerFields() {
    const type = document.getElementById('custom-type').value;
    const answersSection = document.getElementById('answers-section');
    
    if (type === 'multiple_choice') {
        answersSection.classList.remove('hidden');
        if (answerCount === 0) {
            for (let i = 0; i < 4; i++) {
                addAnswer();
            }
        }
    } else {
        answersSection.classList.add('hidden');
    }
}

function addAnswer() {
    const answersList = document.getElementById('answers-list');
    const answerId = answerCount++;
    
    const answerDiv = document.createElement('div');
    answerDiv.className = 'flex items-center gap-2';
    answerDiv.innerHTML = `
        <input type="radio" name="correct_answer" value="${answerId}" ${answerId === 0 ? 'checked' : ''} required
               class="w-5 h-5 text-blue-600" onchange="updateCorrectAnswers(${answerId})">
        <input type="hidden" name="answers[${answerId}][is_correct]" value="0" id="is_correct_${answerId}">
        <input type="text" name="answers[${answerId}][text]" required
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
               placeholder="Đáp án ${String.fromCharCode(65 + answerId)}">
        <button type="button" onclick="this.parentElement.remove()" 
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    answersList.appendChild(answerDiv);
    
    // Set first answer as correct by default
    if (answerId === 0) {
        updateCorrectAnswers(0);
    }
}

function updateCorrectAnswers(correctId) {
    // Reset all answers to incorrect
    document.querySelectorAll('[id^="is_correct_"]').forEach(input => {
        input.value = '0';
    });
    // Set selected answer as correct
    const correctInput = document.getElementById('is_correct_' + correctId);
    if (correctInput) {
        correctInput.value = '1';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    toggleAnswerFields();
});
</script>

<style>
.tab-button.active {
    border-color: #3b82f6;
    color: #3b82f6;
}
.tab-button {
    border-color: transparent;
    color: #6b7280;
}
</style>
@endsection
