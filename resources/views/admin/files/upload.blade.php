@extends('admin.layout')

@section('title', 'Tải lên file')

@section('page-title', 'Tải lên file')
@section('page-description', 'Tải lên tài liệu, bài giảng, đề thi')

@section('content')
<div class="space-y-6">
    <!-- Nút quay về -->
    <div>
        <a href="{{ route('admin.files.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            <span class="font-medium">Quay về danh sách</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('admin.files.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Tiêu đề -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-2">
                    Tiêu đề <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Nhập tiêu đề file..."
                    required>
            </div>

            <!-- Chọn file -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-2">
                    Chọn file <span class="text-red-500">*</span>
                </label>
                <input type="file" name="file" id="fileInput"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                    required>
                <p class="text-sm text-slate-500 mt-2">
                    <i data-feather="info" class="w-4 h-4 inline mr-1"></i>
                    Hỗ trợ: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Tối đa 50MB)
                </p>
            </div>

            <!-- Môn học -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Môn học</label>
                <select name="subject_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Chọn môn học (tùy chọn) --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Thư mục -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-2">
                    Thư mục <span class="text-red-500">*</span>
                </label>
                <select name="folder" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="general" {{ old('folder') == 'general' ? 'selected' : '' }}>📁 Chung</option>
                    <option value="lecture" {{ old('folder') == 'lecture' ? 'selected' : '' }}>📚 Bài giảng</option>
                    <option value="exam" {{ old('folder') == 'exam' ? 'selected' : '' }}>📝 Đề thi</option>
                    <option value="homework" {{ old('folder') == 'homework' ? 'selected' : '' }}>✏️ Bài tập</option>
                </select>
            </div>

            <!-- Mô tả -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Mô tả</label>
                <textarea name="description" rows="4" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập mô tả chi tiết về file...">{{ old('description') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i data-feather="upload" class="w-4 h-4"></i>
                    Tải lên
                </button>
                <a href="{{ route('admin.files.index') }}" class="px-6 py-2.5 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview file name
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName && !document.querySelector('input[name="title"]').value) {
            // Auto-fill title with filename (without extension)
            const nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
            document.querySelector('input[name="title"]').value = nameWithoutExt;
        }
    });
</script>
@endsection
