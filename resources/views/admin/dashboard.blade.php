@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Bảng điều khiển')
@section('page-description', 'Tổng quan hệ thống E-Learning')

@section('content')

    @php
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Schema;
        use Carbon\Carbon;
// ===== THỐNG KÊ BIỂU ĐỒ 12 THÁNG GẦN NHẤT =====
$months = collect(range(1, 12))->map(function ($m) {
    return Carbon::now()->startOfYear()->addMonths($m - 1)->format('Y-m');
});

// Dữ liệu Users
$userChart = [];
foreach ($months as $month) {
    if (Schema::hasTable('account')) {
        $userChart[] = DB::table('account')
            ->where('created_at', 'like', $month . '%')
            ->count();
    } elseif (Schema::hasTable('users')) {
        $userChart[] = DB::table('users')
            ->where('created_at', 'like', $month . '%')
            ->count();
    } else {
        $userChart[] = 0;
    }
}

// Dữ liệu Exams
$examChart = [];
foreach ($months as $month) {
    if (Schema::hasTable('exams')) {
        $examChart[] = DB::table('exams')
            ->where('created_at', 'like', $month . '%')
            ->count();
    } else {
        $examChart[] = 0;
    }
}

// Dữ liệu Forum
$forumChart = [];
foreach ($months as $month) {
    if (Schema::hasTable('forumquestions')) {
        $forumChart[] = DB::table('forumquestions')
            ->where('created_at', 'like', $month . '%')
            ->count();
    } else {
        $forumChart[] = 0;
    }
}
        // =========================
        // 1. THỐNG KÊ NGƯỜI DÙNG
        // =========================

        $totalUsers    = 0;
        $totalStudents = 0;
        $totalTeachers = 0;
        $totalAdmins   = 0;

        if (Schema::hasTable('account')) {
            // Trường hợp DB có bảng `account` (giống file learning3.sql)
            $totalUsers    = DB::table('account')->count();
            $totalStudents = DB::table('account')->where('user_role', 'student')->count();
            $totalTeachers = DB::table('account')->where('user_role', 'teacher')->count();
            $totalAdmins   = DB::table('account')->where('user_role', 'admin')->count();
        } elseif (Schema::hasTable('users')) {
            // Fallback: dùng bảng `users` Laravel (không phân loại role được)
            $totalUsers = DB::table('users')->count();
            // Các số liệu chi tiết để 0 cho an toàn (tránh lỗi cột role)
            $totalStudents = 0;
            $totalTeachers = 0;
            $totalAdmins   = 0;
        }

        // =========================
        // 2. THỐNG KÊ MODULE CHÍNH
        // =========================

        $totalSubjects       = 0;
        $totalExams          = 0;
        $totalForumQuestions = 0;

        if (Schema::hasTable('subjects')) {
            $totalSubjects = DB::table('subjects')->count();
        }

        if (Schema::hasTable('exams')) {
            $totalExams = DB::table('exams')->count();
        }

        if (Schema::hasTable('forumquestions')) {
            $totalForumQuestions = DB::table('forumquestions')->count();
        }

        // =========================
        // 3. BÀI THI GẦN ĐÂY
        // =========================

        $recentExams = collect(); // collection rỗng mặc định

       // ======= BÀI THI GẦN ĐÂY – AUTO CHECK COLUMN =======
$recentExams = collect();

if (Schema::hasTable('exams')) {

    // 1) Tìm cột ID hợp lệ
    $examIdColumn = null;
    foreach (['exam_id', 'id', 'ex_id', 'test_id'] as $col) {
        if (Schema::hasColumn('exams', $col)) {
            $examIdColumn = $col;
            break;
        }
    }

    // 2) Tìm cột Title hợp lệ
    $examTitleColumn = null;
    foreach (['exam_title', 'title', 'name'] as $col) {
        if (Schema::hasColumn('exams', $col)) {
            $examTitleColumn = $col;
            break;
        }
    }

    // 3) Tìm cột Date hợp lệ
    $examDateColumn = null;
    foreach (['exam_date', 'date', 'created_at'] as $col) {
        if (Schema::hasColumn('exams', $col)) {
            $examDateColumn = $col;
            break;
        }
    }

    // 4) Query an toàn – luôn chạy được
    if ($examIdColumn) {
        $recentExams = DB::table('exams')
            ->orderByDesc($examIdColumn)
            ->limit(5)
            ->get();
    }
}

        // =========================
        // 4. NGƯỜI DÙNG MỚI
        // =========================

        $latestUsers = collect();

        if (Schema::hasTable('account')) {
            $latestUsers = DB::table('account')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        } elseif (Schema::hasTable('users')) {
            $latestUsers = DB::table('users')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }
    @endphp

    {{-- ===== HÀNG 1: THỐNG KÊ NHANH ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        {{-- Tổng người dùng --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Tổng người dùng</p>
                <p class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ number_format($totalUsers) }}
                </p>

                {{-- Nếu có số liệu chi tiết thì hiển thị, còn không thì ẩn dòng này --}}
                @if($totalStudents || $totalTeachers || $totalAdmins)
                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ number_format($totalStudents) }} học sinh •
                        {{ number_format($totalTeachers) }} giáo viên •
                        {{ number_format($totalAdmins) }} admin
                    </p>
                @endif
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center">
                <i data-feather="users" class="w-5 h-5 text-indigo-600"></i>
            </div>
        </div>

        {{-- Môn học --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Môn học</p>
                <p class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ number_format($totalSubjects) }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Quản lý trong phần Môn học & Chủ đề
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-violet-50 flex items-center justify-center">
                <i data-feather="book-open" class="w-5 h-5 text-violet-600"></i>
            </div>
        </div>

        {{-- Bài thi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Bài thi</p>
                <p class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ number_format($totalExams) }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Tạo & cấu hình trong phần Exams
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center">
                <i data-feather="file-text" class="w-5 h-5 text-amber-500"></i>
            </div>
        </div>

        {{-- Câu hỏi diễn đàn --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Câu hỏi diễn đàn</p>
                <p class="text-2xl font-semibold text-slate-900 mt-1">
                    {{ number_format($totalForumQuestions) }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Từ module Forum (Hỏi đáp)
                </p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-fuchsia-50 flex items-center justify-center">
                <i data-feather="message-circle" class="w-5 h-5 text-fuchsia-600"></i>
            </div>
        </div>

    </div>

    {{-- ===== HÀNG 2: BIỂU ĐỒ (placeholder) + ĐIỀU HƯỚNG NHANH ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

        {{-- Biểu đồ hoạt động (placeholder) --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Thống kê hoạt động</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Biểu đồ người dùng, bài thi, câu hỏi theo thời gian.
                    </p>
                </div>
            </div>
            <div id="activityChart" class="h-64 w-full"></div>

        </div>

        {{-- Điều hướng nhanh --}}
<div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl shadow-sm text-white p-4">

    <h2 class="text-sm font-semibold mb-3">Điều hướng nhanh</h2>

    {{-- Các link điều hướng cũ --}}
    <div class="space-y-2 text-sm">
        @if (Route::has('admin.students.index'))
            <a href="{{ route('admin.students.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                <span>Quản lý người dùng</span>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        @endif

        @if (Route::has('admin.subjects.index'))
            <a href="{{ route('admin.subjects.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                <span>Quản lý môn học</span>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        @endif

        @if (Route::has('admin.exams.index'))
            <a href="{{ route('admin.exams.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                <span>Quản lý bài thi</span>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        @endif

        @if (Route::has('forum.index'))
            <a href="{{ route('forum.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                <span>Diễn đàn hỏi đáp</span>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        @endif

        @if (Route::has('admin.statistics.index'))
            <a href="{{ route('admin.statistics.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 transition">
                <span>Thống kê chi tiết</span>
                <i data-feather="bar-chart-2" class="w-4 h-4"></i>
            </a>
        @endif
    </div>

</div>

    </div>

    {{-- ===== HÀNG 3: BÀI THI GẦN ĐÂY + NGƯỜI DÙNG MỚI ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

        {{-- Bài thi gần đây --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <h2 class="text-sm font-semibold text-slate-900 mb-3">Bài thi gần đây</h2>

            <div class="space-y-2 text-sm">
                @forelse($recentExams as $exam)
                    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                        <div>
                            <p class="font-medium text-slate-800">
    {{ $exam->{$examTitleColumn} ?? ('Bài thi #' . ($exam->{$examIdColumn} ?? 'N/A')) }}
</p>

<p class="text-xs text-slate-500 mt-0.5">
    @if (!empty($exam->{$examDateColumn}))
        {{ \Carbon\Carbon::parse($exam->{$examDateColumn})->format('d/m/Y') }}
    @else
        Không có ngày thi
    @endif
</p>
                        </div>

                        @if (Route::has('admin.exams.edit'))
                            <a href="{{ route('admin.exams.edit', $exam->{$examIdColumn}) }}"
                               class="text-xs font-medium text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1">
                                Sửa
                                <i data-feather="edit-3" class="w-3 h-3"></i>
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Chưa có bài thi nào.</p>
                @endforelse
            </div>
        </div>

        {{-- Người dùng mới --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <h2 class="text-sm font-semibold text-slate-900 mb-3">Người dùng mới đăng ký</h2>

            <div class="space-y-2 text-sm">
                @forelse($latestUsers as $user)
                    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                        <div class="flex items-center gap-3">
                            @php
                                // Tên & email tùy thuộc bảng: account hoặc users
                                $nameField  = isset($user->user_name) ? 'user_name' : 'name';
                                $emailField = isset($user->user_email) ? 'user_email' : 'email';

                                $displayName  = $user->{$nameField}  ?? 'Không rõ tên';
                                $displayEmail = $user->{$emailField} ?? 'Không có email';

                                $initial = 'U';
                                if (!empty($displayName)) {
                                    $initial = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
                                }

                                $roleText = $user->user_role ?? null;
                            @endphp

                            <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-semibold">
                                {{ $initial }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">
                                    {{ $displayName }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $displayEmail }}
                                </p>
                            </div>
                        </div>

                        @if($roleText)
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                {{ $roleText }}
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Chưa có người dùng mới.</p>
                @endforelse
            </div>
        </div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    var options = {
        chart: {
            type: 'line',
            height: 260,
            toolbar: { show: false }
        },
        stroke: {
            width: 3,
            curve: 'smooth'
        },
        series: [
            {
                name: 'Người dùng',
                data: @json($userChart),
                color: '#6366F1'
            },
            {
                name: 'Bài thi',
                data: @json($examChart),
                color: '#F59E0B'
            },
            {
                name: 'Câu hỏi diễn đàn',
                data: @json($forumChart),
                color: '#EC4899'
            }
        ],
        xaxis: {
            categories: @json($months->map(fn($m) => \Carbon\Carbon::parse($m.'-01')->format('m/Y'))),
            labels: { style: { colors: '#6B7280', fontSize: '12px' } }
        },
        yaxis: {
            labels: { style: { colors: '#6B7280' } }
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 4
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            labels: { colors: '#374151' }
        }
    };

    var chart = new ApexCharts(document.querySelector("#activityChart"), options);
    chart.render();
});
</script>
@endsection
