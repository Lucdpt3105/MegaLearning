@extends('admin.layout')

@section('title', 'Chi tiết Người dùng')
@section('page-title', 'Chi tiết Người dùng')
@section('page-description', 'Thông tin chi tiết tài khoản')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">👤 Chi tiết Người dùng</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 text-center">
                <!-- Avatar -->
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" class="w-32 h-32 rounded-full mx-auto mb-4" alt="{{ $user->name }}">
                @else
                    <div class="w-32 h-32 rounded-full bg-linear-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <!-- Name -->
                <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                
                <!-- Role -->
                @if($user->roles->first())
                    @php
                        $role = $user->roles->first()->name;
                        $badgeClass = match($role) {
                            'admin' => 'bg-red-100 text-red-800',
                            'teacher' => 'bg-green-100 text-green-800',
                            'student' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full {{ $badgeClass }} mt-2">
                        {{ ucfirst($role) }}
                    </span>
                @endif

                <!-- Status -->
                <div class="mt-4">
                    @if($user->is_locked)
                        <div class="bg-red-100 text-red-800 p-3 rounded-lg">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Tài khoản đã bị khóa
                        </div>
                    @else
                        <div class="bg-green-100 text-green-800 p-3 rounded-lg">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tài khoản hoạt động
                        </div>
                    @endif
                </div>

                <!-- Bio -->
                @if($user->bio)
                    <p class="text-gray-600 text-sm mt-4">{{ $user->bio }}</p>
                @endif
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mt-4">
                <h4 class="font-bold text-gray-800 mb-4">📧 Liên hệ</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Email:</span><br>
                        <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">{{ $user->email }}</a>
                    </div>
                    @if($user->phone)
                        <div>
                            <span class="text-gray-600">Số điện thoại:</span><br>
                            <span class="font-medium">{{ $user->phone }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-gray-600">Ngày tham gia:</span><br>
                        <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Cập nhật lần cuối:</span><br>
                        <span class="font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            @if($user->hasRole('teacher'))
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->teachingSubjects->count() }}</h3>
                        <p class="text-sm opacity-90">Môn học giảng dạy</p>
                    </div>
                    <div class="bg-green-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->teachingClasses->count() }}</h3>
                        <p class="text-sm opacity-90">Lớp học</p>
                    </div>
                    <div class="bg-purple-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->createdExams->count() }}</h3>
                        <p class="text-sm opacity-90">Đề thi đã tạo</p>
                    </div>
                </div>
            @elseif($user->hasRole('student'))
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->enrolledClasses->count() }}</h3>
                        <p class="text-sm opacity-90">Lớp đã đăng ký</p>
                    </div>
                    <div class="bg-yellow-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->examSubmissions->count() }}</h3>
                        <p class="text-sm opacity-90">Bài thi đã làm</p>
                    </div>
                    <div class="bg-green-500 text-white rounded-xl p-6">
                        <h3 class="text-3xl font-bold">{{ $user->grades->avg('score') ? number_format($user->grades->avg('score'), 1) : 'N/A' }}</h3>
                        <p class="text-sm opacity-90">Điểm TB</p>
                    </div>
                </div>
            @endif

            <!-- Activity Log -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="font-bold text-gray-800">🕐 Nhật ký hoạt động gần đây</h4>
                </div>
                <div class="p-6">
                    @if($user->activityLogs && $user->activityLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-left text-gray-600 border-b">
                                    <tr>
                                        <th class="pb-2">Thời gian</th>
                                        <th class="pb-2">Hoạt động</th>
                                        <th class="pb-2">Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($user->activityLogs->take(10) as $log)
                                        <tr>
                                            <td class="py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-2">
                                                <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $log->action }}</span>
                                            </td>
                                            <td class="py-2 text-gray-600">{{ $log->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Chưa có hoạt động nào được ghi nhận.</p>
                    @endif
                </div>
            </div>

            <!-- Teacher's Subjects -->
            @if($user->hasRole('teacher') && $user->teachingSubjects->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h4 class="font-bold text-gray-800">📚 Môn học giảng dạy</h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($user->teachingSubjects as $subject)
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h5 class="font-medium text-gray-800">{{ $subject->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $subject->code }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full {{ $subject->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($subject->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Student's Classes -->
            @if($user->hasRole('student') && $user->enrolledClasses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h4 class="font-bold text-gray-800">🎓 Lớp học đã đăng ký</h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($user->enrolledClasses as $class)
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h5 class="font-medium text-gray-800">{{ $class->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $class->subject->name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $class->students->count() }} học viên
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
