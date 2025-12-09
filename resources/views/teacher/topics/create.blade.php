@extends('layouts.app')

@section('title', 'Tạo chủ đề mới - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('teacher.topics.index', request()->only('subject_id')) }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tạo chủ đề mới</h1>
        <p class="text-gray-600">Thêm chủ đề học tập mới vào môn học</p>
    </div>

    <!-- Form -->
    <form action="{{ route('teacher.topics.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                    📚 Môn học <span class="text-red-500">*</span>
                </label>
                <select 
                    name="subject_id" 
                    id="subject_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror"
                    required
                >
                    <option value="">-- Chọn môn học --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Topic Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    📝 Tên chủ đề <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name') }}"
                    placeholder="Ví dụ: Giới thiệu về Laravel, Phương trình bậc 2..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    📄 Mô tả
                </label>
                <textarea 
                    name="description" 
                    id="description"
                    rows="4"
                    placeholder="Mô tả chi tiết về chủ đề này, nội dung sẽ học..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order & Duration -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                        🔢 Thứ tự
                    </label>
                    <input 
                        type="number" 
                        name="order" 
                        id="order"
                        value="{{ old('order') }}"
                        placeholder="Tự động nếu bỏ trống"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('order') border-red-500 @enderror"
                    >
                    <p class="mt-1 text-sm text-gray-500">Để trống để tự động xếp cuối</p>
                    @error('order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                        ⏱️ Thời lượng (phút)
                    </label>
                    <input 
                        type="number" 
                        name="duration" 
                        id="duration"
                        value="{{ old('duration') }}"
                        placeholder="Ví dụ: 45, 90, 120..."
                        min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('duration') border-red-500 @enderror"
                    >
                    <p class="mt-1 text-sm text-gray-500">Thời gian ước tính để học</p>
                    @error('duration')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Resources -->
            <div x-data="resourceManager()">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    🔗 Tài nguyên học tập
                </label>
                <p class="text-sm text-gray-500 mb-3">Thêm link video, tài liệu tham khảo, website...</p>
                
                <div class="space-y-3">
                    <template x-for="(resource, index) in resources" :key="index">
                        <div class="flex items-center space-x-2">
                            <input 
                                type="url"
                                :name="'resources[' + index + ']'"
                                x-model="resources[index]"
                                placeholder="https://example.com/resource"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                            <button 
                                type="button"
                                @click="removeResource(index)"
                                class="p-3 text-red-600 hover:bg-red-50 rounded-lg transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <button 
                        type="button"
                        @click="addResource()"
                        class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-indigo-500 hover:text-indigo-600 transition flex items-center justify-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Thêm tài nguyên</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('teacher.topics.index', request()->only('subject_id')) }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                Hủy
            </a>
            <button 
                type="submit"
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-lg transition"
            >
                Tạo chủ đề
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function resourceManager() {
    return {
        resources: [''],
        
        addResource() {
            this.resources.push('');
        },
        
        removeResource(index) {
            if (this.resources.length > 1) {
                this.resources.splice(index, 1);
            }
        }
    }
}
</script>
@endpush
@endsection
