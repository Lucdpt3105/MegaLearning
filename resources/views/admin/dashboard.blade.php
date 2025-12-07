@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Tổng quan hệ thống E-Learning')

@section('content')
    {{-- Tiêu đề trong nội dung --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-slate-900">Tổng quan hệ thống E-Learning</h2>
        <p class="text-sm text-slate-500 mt-1">
            Thống kê nhanh về môn học, chủ đề, câu hỏi và đề thi trong hệ thống.
        </p>
    </div>

    {{-- 4 stats cards trên cùng --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Tổng môn học --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1">Tổng môn học</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">24</span>
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                        +12%
                    </span>
                </div>
                <p class="text-xs text-emerald-600 mt-1">↑ 12% so với tháng trước</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-2xl">
                📚
            </div>
        </div>

        {{-- Tổng chủ đề --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1">Tổng chủ đề</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">156</span>
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                        +8%
                    </span>
                </div>
                <p class="text-xs text-emerald-600 mt-1">↑ 8% so với tháng trước</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-2xl">
                📖
            </div>
        </div>

        {{-- Tổng câu hỏi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1">Tổng câu hỏi</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">1,248</span>
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                        +15%
                    </span>
                </div>
                <p class="text-xs text-emerald-600 mt-1">↑ 15% so với tháng trước</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-2xl">
                ❓
            </div>
        </div>

        {{-- Tổng đề thi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1">Tổng đề thi</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">87</span>
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                        +5%
                    </span>
                </div>
                <p class="text-xs text-emerald-600 mt-1">↑ 5% so với tháng trước</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-2xl">
                📝
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <h3 class="text-base font-semibold text-slate-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.subjects.create') }}"
               class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl px-4 py-5
                      hover:border-indigo-500 hover:bg-indigo-50 transition group">
                <span class="text-2xl mb-2 group-hover:scale-110 transform transition">➕</span>
                <span class="text-sm font-medium text-slate-700">Thêm môn học</span>
            </a>

            <a href="{{ route('admin.topics.create') }}"
               class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl px-4 py-5
                      hover:border-emerald-500 hover:bg-emerald-50 transition group">
                <span class="text-2xl mb-2 group-hover:scale-110 transform transition">➕</span>
                <span class="text-sm font-medium text-slate-700">Thêm chủ đề</span>
            </a>

            <a href="{{ route('admin.questions.create') }}"
               class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl px-4 py-5
                      hover:border-amber-500 hover:bg-amber-50 transition group">
                <span class="text-2xl mb-2 group-hover:scale-110 transform transition">➕</span>
                <span class="text-sm font-medium text-slate-700">Thêm câu hỏi</span>
            </a>

            <a href="{{ route('admin.exams.create') }}"
               class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl px-4 py-5
                      hover:border-purple-500 hover:bg-purple-50 transition group">
                <span class="text-2xl mb-2 group-hover:scale-110 transform transition">➕</span>
                <span class="text-sm font-medium text-slate-700">Tạo đề thi</span>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Môn học mới nhất --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Môn học mới nhất</h3>
            </div>
            <div class="px-4 py-4 space-y-2">
                @for($i = 1; $i <= 5; $i++)
                    <div class="flex items-center justify-between px-2 py-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center mr-3
                                        w-10 h-10 rounded-xl
                                        bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold">
                                {{ chr(64 + $i) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Môn học {{ $i }}</p>
                                <p class="text-xs text-slate-500">{{ $i * 5 }} chủ đề</p>
                            </div>
                        </div>
                        <a href="#"
                           class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Xem →
                        </a>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Đề thi gần đây --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Đề thi gần đây</h3>
            </div>
            <div class="px-4 py-4 space-y-2">
                @for($i = 1; $i <= 5; $i++)
                    <div class="flex items-center justify-between px-2 py-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center mr-3
                                        w-10 h-10 rounded-xl
                                        bg-gradient-to-br from-purple-500 to-violet-600 text-white font-bold">
                                {{ $i }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Đề thi {{ $i }}</p>
                                <p class="text-xs text-slate-500">{{ $i * 10 }} câu hỏi • 60 phút</p>
                            </div>
                        </div>
                        <a href="#"
                           class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Xem →
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
