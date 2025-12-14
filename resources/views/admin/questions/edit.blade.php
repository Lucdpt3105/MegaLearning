@extends('admin.layout')

@section('title', 'Chỉnh sửa câu hỏi')
@section('page-title', 'Chỉnh sửa câu hỏi')
@section('page-description', 'Cập nhật thông tin câu hỏi')

@section('content')

<!-- Header Actions -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Chỉnh sửa câu hỏi</h3>
        <p class="text-sm text-gray-600">ID: {{ $question->id }}</p>
    </div>
    
    <a href="{{ route('admin.questions.index') }}" 
       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
        <i data-feather="arrow-left" class="w-5 h-5"></i>
        Quay lại
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST" id="questionForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Môn học <span class="text-red-500">*</span>
                            </label>
                            <select id="subject_id" name="subject_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Chọn môn học</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                Nội dung câu hỏi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" required rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Nhập nội dung câu hỏi...">{{ old('content', $question->content) }}</textarea>
                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Loại câu hỏi <span class="text-red-500">*</span>
                                </label>
                                <select id="type" name="type" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                                    <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                                    <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>Tự luận</option>
                                </select>
                            </div>

                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">
                                    Độ khó <span class="text-red-500">*</span>
                                </label>
                                <select id="difficulty" name="difficulty" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Dễ</option>
                                    <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Trung bình</option>
                                    <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Khó</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="explanation" class="block text-sm font-medium text-gray-700 mb-1">
                                Giải thích (tùy chọn)
                            </label>
                            <textarea id="explanation" name="explanation" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Nhập giải thích cho câu trả lời...">{{ old('explanation', $question->explanation) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Answers Section -->
                <div id="answersSection" class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Đáp án</h3>
                    <p class="text-sm text-gray-600 mb-4">Chỉ áp dụng cho câu hỏi trắc nghiệm và đúng/sai</p>
                    
                    <div id="answersList" class="space-y-3">
                        @foreach($question->answers->sortBy('order') as $index => $answer)
                        <div class="answer-item flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-semibold text-sm">
                                {{ chr(65 + $index) }}
                            </span>
                            <input type="text" name="answers[{{ $index }}][content]" required
                                   value="{{ old('answers.'.$index.'.content', $answer->content) }}"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Nhập nội dung đáp án...">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="answers[{{ $index }}][is_correct]" value="1"
                                       {{ old('answers.'.$index.'.is_correct', $answer->is_correct) ? 'checked' : '' }}
                                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Đúng</span>
                            </label>
                            <input type="hidden" name="answers[{{ $index }}][order]" value="{{ $index }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t pt-6 flex items-center justify-between">
                    <a href="{{ route('admin.questions.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
