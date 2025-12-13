@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header with Back Button -->
        <div class="mb-6">
            <a href="{{ route('forum.index', ['sort' => 'my_post']) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-indigo-600 font-medium mb-4 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay Lại Diễn Đàn
            </a>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Tạo Bài Viết Mới</h1>
                    <p class="text-gray-600 font-medium">Chia sẻ suy nghĩ của bạn với cộng đồng</p>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 rounded-xl border-2 border-red-300 bg-gradient-to-br from-red-50 to-red-100 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="mb-2 font-bold text-red-900">Vui lòng sửa các lỗi sau:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-200 overflow-hidden">
            <!-- User Info Header -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-5 border-b-2 border-gray-200">
                <div class="flex items-center gap-3">
                    @php
                        $currentUserId = auth()->user()->id ?? 1;
                        $currentUserName = auth()->user()->name ?? 'User';
                    @endphp
                    <img src="https://randomuser.me/api/portraits/{{ $currentUserId % 2 == 0 ? 'women' : 'men' }}/{{ $currentUserId }}.jpg" 
                         alt="{{ $currentUserName }}" 
                         class="w-12 h-12 rounded-full ring-4 ring-white shadow-md object-cover">
                    <div>
                        <p class="font-bold text-gray-900">{{ $currentUserName }}</p>
                        <p class="text-sm text-gray-600">Đang đăng lên Diễn Đàn Công Khai</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Title Input -->
                <div>
                    <label for="title" class="mb-2 flex items-center gap-2 text-sm font-bold text-gray-900">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tiêu Đề Bài Viết
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Bài viết của bạn nói về gì? Hãy đặt tiêu đề hấp dẫn!"
                        class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 font-medium shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition"
                    />
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Content Textarea -->
                <div>
                    <label for="content" class="mb-2 flex items-center gap-2 text-sm font-bold text-gray-900">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Nội Dung Bài Viết
                    </label>
                    <div class="relative">
                        <textarea
                            id="content"
                            name="content"
                            rows="12"
                            required
                            placeholder="Chia sẻ suy nghĩ, ý tưởng, câu hỏi hoặc bất cứ điều gì bạn muốn thảo luận với cộng đồng..."
                            class="block w-full resize-y rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 font-medium shadow-sm placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition"
                        >{{ old('content') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400 font-medium bg-white px-2 py-1 rounded-md">
                            <span id="charCount">0</span> ký tự
                        </div>
                    </div>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-gray-900">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Thêm Hình Ảnh (Tùy Chọn)
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewImage(event)"
                        />
                        <label for="image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div id="imagePreviewContainer" class="hidden w-full h-full p-2">
                                <img id="imagePreview" src="" alt="Preview" class="w-full h-full object-contain rounded-lg">
                            </div>
                            <div id="imagePlaceholder" class="flex flex-col items-center justify-center py-6">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm text-gray-600 font-medium">Nhấp để tải lên hoặc kéo và thả</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF tối đa 2MB</p>
                            </div>
                        </label>
                        <button type="button" id="removeImage" onclick="removeImage()" class="hidden absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tags Input -->
                <div>
                    <label for="tags" class="mb-2 flex items-center gap-2 text-sm font-bold text-gray-900">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Thêm Thẻ (Tùy Chọn)
                    </label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        value="{{ old('tags') }}"
                        placeholder="ví dụ: Laravel, PHP, JavaScript (phân cách bằng dấu phẩy)"
                        class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 font-medium shadow-sm placeholder:text-gray-400 focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-100 transition"
                        oninput="updateTagsPreview(this.value)"
                    />
                    <div id="tagsPreview" class="mt-2 flex flex-wrap gap-2"></div>
                    <p class="mt-1 text-xs text-gray-500">Phân cách các thẻ bằng dấu phẩy. Nên dùng tối đa 5 thẻ.</p>
                    @error('tags')
                        <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tips Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-blue-900 mb-2">Mẹo Viết Bài</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">•</span>
                                    <span>Tiêu đề rõ ràng và cụ thể</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">•</span>
                                    <span>Cung cấp nội dung chi tiết để người khác hiểu rõ</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">•</span>
                                    <span>Tôn trọng và tuân thủ quy tắc cộng đồng</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t-2 border-gray-200">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Tất cả các trường có dấu * là bắt buộc</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('forum.index', ['sort' => 'my_post']) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl shadow-sm transition-all hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hủy
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg transition-all hover:shadow-xl hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Đăng Bài
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Preview Card (Optional) -->
        <div class="mt-6 bg-white rounded-2xl shadow-lg border-2 border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <h3 class="font-bold text-gray-900">Xem Trước Bài Viết</h3>
            </div>
            <div class="text-sm text-gray-600">
                <p>Bài viết của bạn sẽ hiển thị ở đây khi bạn nhập...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
const contentTextarea = document.getElementById('content');
const charCount = document.getElementById('charCount');

if (contentTextarea && charCount) {
    contentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Initial count
    charCount.textContent = contentTextarea.value.length;
}

// Image preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
            document.getElementById('imagePlaceholder').classList.add('hidden');
            document.getElementById('removeImage').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const imageInput = document.getElementById('image');
    imageInput.value = '';
    document.getElementById('imagePreviewContainer').classList.add('hidden');
    document.getElementById('imagePlaceholder').classList.remove('hidden');
    document.getElementById('removeImage').classList.add('hidden');
}

// Tags preview
const tagColors = [
    'bg-indigo-100 text-indigo-700',
    'bg-green-100 text-green-700',
    'bg-blue-100 text-blue-700',
    'bg-purple-100 text-purple-700',
    'bg-pink-100 text-pink-700',
    'bg-orange-100 text-orange-700',
    'bg-red-100 text-red-700',
    'bg-yellow-100 text-yellow-700'
];

function updateTagsPreview(value) {
    const tagsPreview = document.getElementById('tagsPreview');
    const tags = value.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
    
    tagsPreview.innerHTML = '';
    
    tags.forEach((tag, index) => {
        const colorClass = tagColors[index % tagColors.length];
        const tagElement = document.createElement('span');
        tagElement.className = `px-3 py-1.5 ${colorClass} text-sm font-bold rounded-full flex items-center gap-1`;
        tagElement.innerHTML = `
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            ${tag}
        `;
        tagsPreview.appendChild(tagElement);
    });
}

// Initialize tags preview if there's old value
window.addEventListener('DOMContentLoaded', function() {
    const tagsInput = document.getElementById('tags');
    if (tagsInput && tagsInput.value) {
        updateTagsPreview(tagsInput.value);
    }
});
</script>
@endsection