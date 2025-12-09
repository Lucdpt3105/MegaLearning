@extends('layouts.app')

@section('title', 'Tìm kiếm câu hỏi')

@section('content')
<div class="container-fluid p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🔍 Tìm kiếm câu hỏi</h1>
        <p class="text-gray-600">Khám phá và ôn tập các câu hỏi theo chủ đề</p>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('student.questions.browse') }}" id="searchForm">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tìm kiếm câu hỏi hoặc chủ đề
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="VD: Đại số, HTML, Cơ học..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

                <!-- Subject Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Môn học
                    </label>
                    <select 
                        name="subject_id" 
                        id="subjectSelect"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        onchange="loadTopics(this.value)"
                    >
                        <option value="">Tất cả môn học</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Topic Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Chủ đề
                    </label>
                    <select 
                        name="topic_id" 
                        id="topicSelect"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        {{ !request('subject_id') ? 'disabled' : '' }}
                    >
                        <option value="">Chọn môn học trước</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 mt-4">
                <button 
                    type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tìm kiếm
                </button>
                <a 
                    href="{{ route('student.questions.browse') }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Xóa bộ lọc
                </a>
                <div class="ml-auto text-sm text-gray-600">
                    Tìm thấy <strong>{{ $questions->total() }}</strong> câu hỏi
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if($questions->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($questions as $question)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Subject & Topic Tags -->
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    📚 {{ $question->subject->name }}
                                </span>
                                @if($question->topic)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        🏷️ {{ $question->topic->name }}
                                    </span>
                                @endif
                                @if($question->difficulty_level)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $question->difficulty_level === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $question->difficulty_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $question->difficulty_level === 'hard' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ $question->difficulty_level === 'easy' ? '⭐ Dễ' : '' }}
                                        {{ $question->difficulty_level === 'medium' ? '⭐⭐ Trung bình' : '' }}
                                        {{ $question->difficulty_level === 'hard' ? '⭐⭐⭐ Khó' : '' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Question Text -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2">
                                {{ $question->question_text }}
                            </h3>

                            <!-- Answers Preview -->
                            @if($question->answers && $question->answers->count() > 0)
                                <div class="space-y-2 mb-4">
                                    @foreach($question->answers->take(2) as $index => $answer)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <span class="w-6 h-6 rounded-full {{ $answer->is_correct ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center mr-2 font-medium">
                                                {{ chr(65 + $index) }}
                                            </span>
                                            <span class="line-clamp-1">{{ $answer->answer_text }}</span>
                                        </div>
                                    @endforeach
                                    @if($question->answers->count() > 2)
                                        <p class="text-xs text-gray-500 ml-8">
                                            + {{ $question->answers->count() - 2 }} đáp án khác
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- View Details Button -->
                            <a 
                                href="{{ route('student.questions.show', $question->id) }}"
                                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium text-sm transition-colors"
                            >
                                Xem chi tiết
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy câu hỏi nào</h3>
            <p class="text-gray-500 mb-4">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
            <a 
                href="{{ route('student.questions.browse') }}"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Xem tất cả câu hỏi
            </a>
        </div>
    @endif
</div>

<script>
// Load topics when subject changes
function loadTopics(subjectId) {
    const topicSelect = document.getElementById('topicSelect');
    
    if (!subjectId) {
        topicSelect.innerHTML = '<option value="">Chọn môn học trước</option>';
        topicSelect.disabled = true;
        return;
    }
    
    // Show loading
    topicSelect.innerHTML = '<option value="">Đang tải...</option>';
    topicSelect.disabled = true;
    
    // Fetch topics via AJAX
    fetch(`/student/questions/topics?subject_id=${subjectId}`)
        .then(response => response.json())
        .then(topics => {
            topicSelect.innerHTML = '<option value="">Tất cả chủ đề</option>';
            topics.forEach(topic => {
                const option = document.createElement('option');
                option.value = topic.id;
                option.textContent = topic.name;
                topicSelect.appendChild(option);
            });
            topicSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading topics:', error);
            topicSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        });
}

// Load topics on page load if subject is selected
document.addEventListener('DOMContentLoaded', function() {
    const subjectId = document.getElementById('subjectSelect').value;
    if (subjectId) {
        loadTopics(subjectId);
        // Set selected topic after topics are loaded
        const selectedTopicId = '{{ request("topic_id") }}';
        if (selectedTopicId) {
            setTimeout(() => {
                document.getElementById('topicSelect').value = selectedTopicId;
            }, 500);
        }
    }
});
</script>
@endsection
