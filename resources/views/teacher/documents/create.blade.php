@extends('layouts.app')

@section('title', 'Tải lên Tài liệu')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.documents.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tải lên Tài liệu mới</h1>
                <p class="text-gray-600 mt-1">Upload tài liệu và gửi kiểm duyệt</p>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-md p-8">
            <form action="{{ route('teacher.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Tiêu đề <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        placeholder="Nhập tiêu đề tài liệu..."
                        required
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Mô tả nội dung tài liệu..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Môn học <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="subject_id" 
                        name="subject_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Chọn môn học</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ (old('subject_id') ?? request('subject_id')) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Folder (UC-GV-073) -->
                <div class="mb-6">
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-2">
                        Thư mục (Phân chia tài liệu)
                    </label>
                    <input 
                        type="text" 
                        id="folder" 
                        name="folder" 
                        value="{{ old('folder') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('folder') border-red-500 @enderror"
                        placeholder="Ví dụ: Bài giảng, Tài liệu tham khảo, Đề thi..."
                    >
                    <p class="mt-1 text-sm text-gray-500">Để trống nếu không muốn phân loại vào thư mục</p>
                    @error('folder')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        File tài liệu <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Chọn file</span>
                                    <input 
                                        id="file" 
                                        name="file" 
                                        type="file" 
                                        class="sr-only"
                                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip"
                                        required
                                        onchange="displayFileName(this)"
                                    >
                                </label>
                                <p class="pl-1">hoặc kéo thả file vào đây</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP (tối đa 50MB)
                            </p>
                            <p id="file-name" class="text-sm text-indigo-600 font-medium mt-2"></p>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Alert -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Tùy chọn kiểm duyệt</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                Bạn có thể tự phê duyệt tài liệu ngay lập tức hoặc gửi cho Admin kiểm duyệt sau.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Auto Approve Option -->
                <div class="mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="auto_approve" 
                            value="1"
                            {{ old('auto_approve', true) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                        <div>
                            <span class="text-sm font-medium text-gray-900">Tự động phê duyệt tài liệu</span>
                            <p class="text-xs text-gray-500">Tài liệu sẽ được công khai ngay lập tức cho học sinh</p>
                        </div>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('teacher.documents.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all">
                        Tải lên Tài liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileNameDisplay = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
        fileNameDisplay.textContent = `📄 ${fileName} (${fileSize} MB)`;
    }
}
</script>
@endsection
