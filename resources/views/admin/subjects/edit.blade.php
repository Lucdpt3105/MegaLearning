@extends('admin.layout')

@section('title', 'Chỉnh sửa môn học')
@section('page-title', 'Chỉnh sửa môn học')
@section('page-description', 'Cập nhật thông tin môn học')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <span class="text-2xl mr-3">❌</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.subjects.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                <span class="mr-2">←</span>
                Quay lại danh sách
            </a>
            
            <div class="text-right">
                <p class="text-sm text-gray-500">ID: <span class="font-semibold text-gray-700">#{{ $subject->id }}</span></p>
                <p class="text-xs text-gray-400">Cập nhật: {{ $subject->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <span class="mr-3">✏️</span>
                            Chỉnh sửa thông tin
                        </h2>
                        <p class="text-indigo-100 mt-1">Cập nhật thông tin môn học: <strong>{{ $subject->name }}</strong></p>
                    </div>

                    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST" class="p-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Subject Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tên môn học <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $subject->name) }}"
                                   required
                                   placeholder="Ví dụ: Lập Trình Web"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('name') border-red-500 @enderror">
                            <p class="mt-2 text-sm text-gray-500">📝 Nhập tên môn học rõ ràng và dễ hiểu</p>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <span class="mr-1">⚠️</span> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Subject Code -->
                        <div class="mb-6">
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mã môn học <span class="text-gray-400 font-normal">(Tùy chọn)</span>
                            </label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $subject->code) }}"
                                   placeholder="Ví dụ: WEB101"
                                   maxlength="20"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('code') border-red-500 @enderror">
                            <p class="mt-2 text-sm text-gray-500">🏷️ Mã định danh ngắn gọn cho môn học</p>
                            @error('code')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <span class="mr-1">⚠️</span> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mô tả <span class="text-gray-400 font-normal">(Tùy chọn)</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Nhập mô tả chi tiết về môn học, mục tiêu học tập, nội dung..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description', $subject->description) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">📄 Mô tả giúp học sinh hiểu rõ hơn về môn học</p>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <span class="mr-1">⚠️</span> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-8">
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Trạng thái
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $subject->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                    ✅ Đang hoạt động
                                </option>
                                <option value="inactive" {{ old('status', $subject->status ?? 'active') == 'inactive' ? 'selected' : '' }}>
                                    ⏸️ Tạm ngưng
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <span class="mr-1">⚠️</span> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.subjects.index') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition font-medium shadow-md hover:shadow-lg">
                                <span class="mr-2">💾</span>
                                Cập nhật môn học
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="mr-2">📊</span>
                        Thống kê
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">📚</span>
                                <span class="text-sm font-medium text-gray-700">Chủ đề</span>
                            </div>
                            <span class="text-xl font-bold text-blue-600">{{ $subject->topics_count ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">📋</span>
                                <span class="text-sm font-medium text-gray-700">Đề thi</span>
                            </div>
                            <span class="text-xl font-bold text-purple-600">{{ $subject->exams_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="mr-2">ℹ️</span>
                        Thông tin
                    </h3>
                    <div class="space-y-3">
                        <div class="pb-3 border-b border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">ID</p>
                            <p class="text-sm font-semibold text-gray-900">#{{ $subject->id }}</p>
                        </div>
                        
                        @if($subject->code)
                        <div class="pb-3 border-b border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">Mã môn học</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $subject->code }}</p>
                        </div>
                        @endif
                        
                        <div class="pb-3 border-b border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">Ngày tạo</p>
                            <p class="text-sm font-medium text-gray-900">{{ $subject->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Cập nhật lần cuối</p>
                            <p class="text-sm font-medium text-gray-900">{{ $subject->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-2 flex items-center">
                        <span class="mr-2">⚠️</span>
                        Khu vực nguy hiểm
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Xóa môn học này sẽ được lưu trữ và có thể khôi phục. Dữ liệu không bị mất vĩnh viễn.
                    </p>
                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('⚠️ BẠN CÓ CHẮC CHẮN MUỐN XÓA MÔN HỌC NÀY?\n\nTên: {{ $subject->name }}\nMã: {{ $subject->code }}\n\nLưu ý: Dữ liệu sẽ được lưu trữ và có thể khôi phục sau này.\n\nNhấn OK để xóa, Cancel để hủy.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-sm hover:shadow-md">
                            <span class="mr-2">🗑️</span>
                            Xóa môn học này
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
