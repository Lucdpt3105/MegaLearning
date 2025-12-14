@extends('admin.layout')

@section('title', 'Ngân hàng câu hỏi')
@section('page-title', 'Ngân hàng câu hỏi')
@section('page-description', 'Quản lý tất cả câu hỏi trong hệ thống')

@section('content')

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
            <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại câu hỏi</label>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Tự luận</option>
                <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó</label>
            <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả</option>
                <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                   placeholder="Nhập từ khóa...">
        </div>

        <div class="md:col-span-4 flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-feather="search" class="w-4 h-4 inline"></i> Lọc
            </button>
            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i data-feather="x" class="w-4 h-4 inline"></i> Xóa bộ lọc
            </a>
            <a href="{{ route('admin.questions.create') }}" class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i data-feather="plus" class="w-4 h-4 inline"></i> Thêm câu hỏi
            </a>
        </div>
    </form>
</div>

<!-- Questions Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nội dung câu hỏi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Loại</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Môn học</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Độ khó</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Người tạo</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($questions as $index => $question)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    
                    <td class="px-4 py-4 text-sm text-gray-900">
                        <div class="max-w-md">{{ Str::limit($question->content, 80) }}</div>
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                            {{ ucfirst($question->type) }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        {{ $question->subject ? $question->subject->name : 'N/A' }}
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm">
                        @if($question->difficulty)
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($question->difficulty) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        {{ $question->creator ? $question->creator->name : 'N/A' }}
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.questions.show', $question) }}" 
                               class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                               title="Xem chi tiết">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            
                            <a href="{{ route('admin.questions.edit', $question) }}" 
                               class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition"
                               title="Chỉnh sửa">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </a>
                            
                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                                        title="Xóa">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-feather="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có câu hỏi</h3>
                        <p class="text-gray-600">Chưa có câu hỏi nào trong hệ thống</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($questions->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $questions->links() }}
    </div>
    @endif
</div>

@endsection
