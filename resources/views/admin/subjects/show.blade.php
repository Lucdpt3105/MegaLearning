@extends('admin.layout')

@section('title', 'Chi tiết môn học')
@section('page-title', $subject->name)
@section('page-description', 'Thông tin chi tiết và thống kê của môn học')

@section('content')
    <!-- Back Button -->
    <a href="{{ route('admin.subjects.index') }}" 
       class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
        <span class="mr-2">←</span>
        Quay lại danh sách
    </a>

    <!-- Subject Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-3">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-3xl font-bold mr-4">
                        {{ strtoupper(substr($subject->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1">{{ $subject->name }}</h1>
                        <div class="flex items-center space-x-3">
                            @if($subject->code)
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                                🏷️ {{ $subject->code }}
                            </span>
                            @endif
                            @if(($subject->status ?? 'active') == 'active')
                            <span class="px-3 py-1 bg-green-400/30 backdrop-blur-sm rounded-lg text-sm font-medium">
                                ✓ Hoạt động
                            </span>
                            @else
                            <span class="px-3 py-1 bg-gray-400/30 backdrop-blur-sm rounded-lg text-sm font-medium">
                                ⏸ Tạm ngưng
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($subject->description)
                <p class="text-white/90 text-base leading-relaxed max-w-3xl">
                    {{ $subject->description }}
                </p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                   class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition font-medium inline-flex items-center">
                    <span class="mr-2">✏️</span>
                    Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Topics List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="mr-2">📚</span>
                        Danh sách chủ đề
                        <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $subject->topics_count ?? 0 }}
                        </span>
                    </h2>
                </div>
                
                @if($subject->topics && $subject->topics->count() > 0)
                <div class="space-y-3">
                    @foreach($subject->topics as $topic)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $topic->name }}</h4>
                                @if($topic->description)
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($topic->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">
                            📚 Chủ đề
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">📖</div>
                    <p>Chưa có chủ đề nào.</p>
                </div>
                @endif
            </div>

            <!-- Exams List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="mr-2">📋</span>
                        Đề thi
                        <span class="ml-2 px-3 py-1 bg-purple-100 text-purple-800 text-sm font-medium rounded-full">
                            {{ $subject->exams_count ?? 0 }}
                        </span>
                    </h2>
                </div>
                
                @if($subject->exams && $subject->exams->count() > 0)
                <div class="space-y-3">
                    @foreach($subject->exams as $exam)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $exam->title }}</h4>
                            <div class="flex items-center space-x-4 mt-1 text-sm text-gray-500">
                                @if($exam->duration)
                                <span>⏱️ {{ $exam->duration }} phút</span>
                                @endif
                                @if($exam->total_marks)
                                <span>📊 {{ $exam->total_marks }} điểm</span>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium">
                            📝 Đề thi
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">📝</div>
                    <p>Chưa có đề thi nào.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Thống kê</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📚</span>
                            <span class="text-sm text-gray-600">Chủ đề</span>
                        </div>
                        <span class="text-xl font-bold text-blue-600">{{ $subject->topics_count ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📋</span>
                            <span class="text-sm text-gray-600">Đề thi</span>
                        </div>
                        <span class="text-xl font-bold text-purple-600">{{ $subject->exams_count ?? 0 }}</span>
                    </div>
                    
                    @if(isset($subject->documents_count))
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📄</span>
                            <span class="text-sm text-gray-600">Tài liệu</span>
                        </div>
                        <span class="text-xl font-bold text-green-600">{{ $subject->documents_count }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Thông tin</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">ID</p>
                        <p class="text-sm font-medium text-gray-900">#{{ $subject->id }}</p>
                    </div>
                    
                    @if($subject->code)
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Mã môn học</p>
                        <p class="text-sm font-medium text-gray-900">{{ $subject->code }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Trạng thái</p>
                        @if(($subject->status ?? 'active') == 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ✓ Đang hoạt động
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            ⏸ Tạm ngưng
                        </span>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Ngày tạo</p>
                        <p class="text-sm font-medium text-gray-900">{{ $subject->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Cập nhật lần cuối</p>
                        <p class="text-sm font-medium text-gray-900">{{ $subject->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Thao tác nhanh</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                       class="block w-full px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-center font-medium">
                        ✏️ Chỉnh sửa môn học
                    </a>
                    
                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('⚠️ BẠN CÓ CHẮC CHẮN MUỐN XÓA MÔN HỌC NÀY?\n\nTên: {{ $subject->name }}\nMã: {{ $subject->code }}\n\nLưu ý: Dữ liệu sẽ được lưu trữ và có thể khôi phục.\n\nNhấn OK để xóa, Cancel để hủy.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="block w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-center font-medium">
                            🗑️ Xóa môn học
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
