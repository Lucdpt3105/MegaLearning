@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Tổng quan hệ thống E-Learning')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Subjects -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng môn học</p>
                    <h3 class="text-3xl font-bold text-gray-800">24</h3>
                    <p class="text-xs text-green-600 mt-2">↑ 12% so với tháng trước</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-2xl">
                    📚
                </div>
            </div>
        </div>

        <!-- Total Topics -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng chủ đề</p>
                    <h3 class="text-3xl font-bold text-gray-800">156</h3>
                    <p class="text-xs text-green-600 mt-2">↑ 8% so với tháng trước</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-2xl">
                    📖
                </div>
            </div>
        </div>

        <!-- Total Questions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng câu hỏi</p>
                    <h3 class="text-3xl font-bold text-gray-800">1,248</h3>
                    <p class="text-xs text-green-600 mt-2">↑ 15% so với tháng trước</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center text-2xl">
                    ❓
                </div>
            </div>
        </div>

        <!-- Total Exams -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tổng đề thi</p>
                    <h3 class="text-3xl font-bold text-gray-800">87</h3>
                    <p class="text-xs text-green-600 mt-2">↑ 5% so với tháng trước</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-2xl">
                    📝
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.subjects.create') }}" 
               class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition group">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">➕</span>
                <span class="text-sm font-medium text-gray-700">Thêm môn học</span>
            </a>

            <a href="{{ route('admin.topics.create') }}" 
               class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition group">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">➕</span>
                <span class="text-sm font-medium text-gray-700">Thêm chủ đề</span>
            </a>

            <a href="{{ route('admin.questions.create') }}" 
               class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition group">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">➕</span>
                <span class="text-sm font-medium text-gray-700">Thêm câu hỏi</span>
            </a>

            <a href="{{ route('admin.exams.create') }}" 
               class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">➕</span>
                <span class="text-sm font-medium text-gray-700">Tạo đề thi</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Subjects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Môn học mới nhất</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                {{ chr(64 + $i) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Môn học {{ $i }}</p>
                                <p class="text-xs text-gray-500">{{ $i * 5 }} chủ đề</p>
                            </div>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Xem →</a>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Đề thi gần đây</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                {{ $i }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Đề thi {{ $i }}</p>
                                <p class="text-xs text-gray-500">{{ $i * 10 }} câu hỏi • 60 phút</p>
                            </div>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Xem →</a>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection
