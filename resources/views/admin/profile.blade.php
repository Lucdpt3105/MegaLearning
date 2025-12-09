@extends('admin.layout')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ sơ cá nhân')
@section('page-description', 'Quản lý thông tin tài khoản của bạn.')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Avatar + Basic Info --}}
    <div class="bg-white shadow rounded-2xl p-6 border border-slate-100">

        <div class="flex flex-col items-center text-center">

            {{-- Avatar --}}
            <div class="relative group">

                <img src="{{ auth()->user()->avatar 
                        ? asset('uploads/avatars/' . auth()->user()->avatar) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff&size=128' }}"
                     class="w-32 h-32 rounded-full shadow-md border-4 border-white object-cover">

                {{-- Upload button --}}
                <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label
                        class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full shadow cursor-pointer hover:bg-indigo-700 transition">
                        <i data-feather="camera" class="w-4 h-4"></i>
                        <input type="file" name="avatar" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

            </div>

            {{-- Name --}}
            <h2 class="text-xl font-semibold mt-4">{{ auth()->user()->name }}</h2>
            <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>

            {{-- Role --}}
            <span class="mt-3 inline-block text-xs px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                {{ ucfirst(auth()->user()->roles->first()->name) }}
            </span>
        </div>

        {{-- Divider --}}
        <hr class="my-6">

        {{-- Account Info --}}
        <div class="text-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-slate-600">Ngày tạo:</span>
                <span class="font-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-slate-600">Lần cập nhật:</span>
                <span class="font-medium">{{ auth()->user()->updated_at->format('d/m/Y') }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-slate-600">Trạng thái:</span>
                <span class="text-green-600 font-semibold">
                    {{ auth()->user()->is_locked ? 'Đã khóa' : 'Hoạt động' }}
                </span>
            </div>
        </div>
    </div>



    {{-- RIGHT: UPDATE INFO + CHANGE PASSWORD --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Update Information --}}
        <div class="bg-white shadow rounded-2xl p-6 border border-slate-100">

            <h3 class="text-lg font-semibold text-slate-800 mb-4">Cập nhật thông tin cá nhân</h3>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium text-slate-600">Họ và tên</label>
                        <input type="text" name="name"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ auth()->user()->name }}">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Email</label>
                        <input type="email" name="email"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 bg-slate-100 cursor-not-allowed"
                               value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-slate-600">Số điện thoại</label>
                        <input type="text" name="phone"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ auth()->user()->phone }}">
                    </div>

                </div>

                <button type="submit"
                        class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow">
                    Lưu thay đổi
                </button>
            </form>
        </div>


        {{-- Change Password --}}
        <div class="bg-white shadow rounded-2xl p-6 border border-slate-100">

            <h3 class="text-lg font-semibold text-slate-800 mb-4">Đổi mật khẩu</h3>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <div>
                        <label class="text-sm font-medium text-slate-600">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Mật khẩu mới</label>
                        <input type="password" name="password"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation"
                               class="mt-1 w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-indigo-500">
                    </div>

                </div>

                <button type="submit"
                        class="mt-4 px-6 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 shadow">
                    Đổi mật khẩu
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
