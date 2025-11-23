@extends('layouts.app')

@section('title', 'Sửa Câu hỏi')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Sửa Câu hỏi</h1>
                <p class="text-gray-600 mt-2">Cập nhật thông tin câu hỏi</p>
            </div>
            <a href="{{ route('teacher.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                ← Quay lại
            </a>
        </div>
    </div>

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

    <!-- Form -->
    <form action="{{ route('teacher.questions.update', $question) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-md p-8">
        @csrf
        @method('PUT')

        <!-- Subject Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Môn học <span class="text-red-500">*</span></label>
            <select name="subject_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror">
                <option value="">-- Chọn môn học --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ (old('subject_id', $question->subject_id) == $subject->id) ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question Type -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại câu hỏi <span class="text-red-500">*</span></label>
            <select name="type" id="questionType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type') border-red-500 @enderror">
                <option value="">-- Chọn loại --</option>
                <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>Tự luận</option>
                <option value="fill_blank" {{ old('type', $question->type) == 'fill_blank' ? 'selected' : '' }}>Điền khuyết</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question Content -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung câu hỏi <span class="text-red-500">*</span></label>
            <textarea name="content" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content', $question->content) }}</textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Difficulty & Points -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó <span class="text-red-500">*</span></label>
                <select name="difficulty" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('difficulty') border-red-500 @enderror">
                    <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Dễ</option>
                    <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Trung bình</option>
                    <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Khó</option>
                </select>
                @error('difficulty')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Điểm <span class="text-red-500">*</span></label>
                <input type="number" name="points" step="0.5" min="0" value="{{ old('points', $question->points) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('points') border-red-500 @enderror">
                @error('points')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Current Image -->
        @if($question->image_url)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh hiện tại</label>
            <img src="{{ asset('storage/' . $question->image_url) }}" alt="Question Image" class="max-w-xs rounded-lg shadow-md">
        </div>
        @endif

        <!-- Question Image -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Thay đổi hình ảnh (tùy chọn)</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('image') border-red-500 @enderror">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Explanation -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Giải thích đáp án (tùy chọn)</label>
            <textarea name="explanation" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('explanation') border-red-500 @enderror">{{ old('explanation', $question->explanation) }}</textarea>
            @error('explanation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Answers Section -->
        <div id="answersSection" class="mb-6" style="display: {{ in_array($question->type, ['multiple_choice', 'true_false']) ? 'block' : 'none' }};">
            <div class="flex items-center justify-between mb-4">
                <label class="block text-sm font-medium text-gray-700">Đáp án <span class="text-red-500">*</span></label>
                <button type="button" id="addAnswerBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm" style="display: {{ $question->type === 'true_false' ? 'none' : 'block' }};">
                    + Thêm đáp án
                </button>
            </div>
            <div id="answersContainer" class="space-y-3">
                @foreach($question->answers as $index => $answer)
                <div class="answer-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="answers[{{ $index + 1 }}][is_correct]" value="1" {{ $answer->is_correct ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded">
                    <input type="text" name="answers[{{ $index + 1 }}][content]" value="{{ $answer->content }}" placeholder="Nhập đáp án {{ $index + 1 }}" required class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @if($index >= 2)
                    <button type="button" onclick="removeAnswer(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Xóa</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('teacher.questions.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                Cập nhật
            </button>
        </div>
    </form>
</div>

<script>
let answerCount = {{ $question->answers->count() }};

document.getElementById('questionType').addEventListener('change', function() {
    const type = this.value;
    const answersSection = document.getElementById('answersSection');
    const answersContainer = document.getElementById('answersContainer');
    
    answersContainer.innerHTML = '';
    answerCount = 0;
    
    if (type === 'multiple_choice' || type === 'true_false') {
        answersSection.style.display = 'block';
        
        if (type === 'true_false') {
            addAnswer('Đúng');
            addAnswer('Sai');
            document.getElementById('addAnswerBtn').style.display = 'none';
        } else {
            document.getElementById('addAnswerBtn').style.display = 'block';
            addAnswer();
            addAnswer();
        }
    } else {
        answersSection.style.display = 'none';
    }
});

document.getElementById('addAnswerBtn').addEventListener('click', function() {
    addAnswer();
});

function addAnswer(defaultContent = '') {
    answerCount++;
    const answerHtml = `
        <div class="answer-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
            <input type="checkbox" name="answers[${answerCount}][is_correct]" value="1" class="w-5 h-5 text-indigo-600 rounded">
            <input type="text" name="answers[${answerCount}][content]" value="${defaultContent}" placeholder="Nhập đáp án ${answerCount}" required class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            ${answerCount > 2 ? '<button type="button" onclick="removeAnswer(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Xóa</button>' : ''}
        </div>
    `;
    
    document.getElementById('answersContainer').insertAdjacentHTML('beforeend', answerHtml);
}

function removeAnswer(button) {
    button.closest('.answer-item').remove();
}
</script>
@endsection
