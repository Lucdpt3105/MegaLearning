@extends('admin.layout')

@section('title', 'Kết quả tìm kiếm')

@section('page-title', 'Kết quả tìm kiếm')
@section('page-description', 'Tìm kiếm: "' . $query . '"')

@section('content')
<div class="space-y-6">
    
    @if($users->isEmpty() && $classes->isEmpty() && $subjects->isEmpty() && $exams->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-feather="search" class="w-8 h-8 text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Không tìm thấy kết quả</h3>
            <p class="text-slate-600">Thử tìm kiếm với từ khóa khác</p>
        </div>
    @endif

    {{-- Users Results --}}
    @if($users->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i data-feather="users" class="w-5 h-5"></i>
                    Người dùng ({{ $users->total() }})
                </h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <span class="text-lg font-semibold text-slate-600">{{ substr($user->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                <p class="text-sm text-slate-600">{{ $user->email }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $user->role === 'teacher' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $user->role === 'student' ? 'bg-green-100 text-green-700' : '' }}
                            ">
                                {{ $user->role === 'admin' ? 'Quản trị viên' : '' }}
                                {{ $user->role === 'teacher' ? 'Giáo viên' : '' }}
                                {{ $user->role === 'student' ? 'Học sinh' : '' }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $users->appends(['q' => $query])->links() }}
            </div>
        </div>
    @endif

    {{-- Classes Results --}}
    @if($classes->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i data-feather="book-open" class="w-5 h-5"></i>
                    Lớp học ({{ $classes->total() }})
                </h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($classes as $class)
                    <a href="{{ route('admin.courses.edit', $class->id) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i data-feather="book-open" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900">{{ $class->name }}</p>
                                <p class="text-sm text-slate-600">Mã: {{ $class->code }} • Giáo viên: {{ $class->teacher->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $classes->appends(['q' => $query])->links() }}
            </div>
        </div>
    @endif

    {{-- Subjects Results --}}
    @if($subjects->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i data-feather="book" class="w-5 h-5"></i>
                    Môn học ({{ $subjects->total() }})
                </h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($subjects as $subject)
                    <a href="{{ route('admin.courses.index') }}?search={{ urlencode($subject->name) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                <i data-feather="book" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900">{{ $subject->name }}</p>
                                <p class="text-sm text-slate-600">{{ \Str::limit($subject->description ?? 'Môn học', 100) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $subjects->appends(['q' => $query])->links() }}
            </div>
        </div>
    @endif

    {{-- Exams Results --}}
    @if($exams->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <i data-feather="file-text" class="w-5 h-5"></i>
                    Bài kiểm tra ({{ $exams->total() }})
                </h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($exams as $exam)
                    <a href="{{ route('admin.courses.index') }}?search={{ urlencode($exam->title) }}" class="block px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                <i data-feather="file-text" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900">{{ $exam->title }}</p>
                                <p class="text-sm text-slate-600">Lớp: {{ $exam->classRoom->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $exams->appends(['q' => $query])->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
