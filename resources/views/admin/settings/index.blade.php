@extends('admin.layout')

@section('title', 'Cài đặt hệ thống')
@section('page-title', 'Cài đặt hệ thống')
@section('page-description', 'Quản lý cấu hình chung, logo, thông báo và bảo mật.')

@section('content')

{{-- Thông báo --}}
@if(session('success'))
    <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-xl">
        ✔ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-xl">
        ✖ {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ============================
        1. CÀI ĐẶT CHUNG
    ============================= --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-semibold mb-4">Cài đặt chung</h2>

        <form action="{{ route('admin.settings.update.general') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-700">Tên website</label>
                    <input type="text" name="site_name"
                           value="{{ setting('site_name') }}"
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-slate-300 focus:border-indigo-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Mô tả website</label>
                    <textarea name="site_description"
                              class="w-full mt-1 px-3 py-2 rounded-xl border border-slate-300 focus:border-indigo-500"
                              rows="3">{{ setting('site_description') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Logo</label>

                    <div class="mt-2 flex items-center gap-4">
                        <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.svg') }}"
                             class="h-12 rounded-lg border" />

                        <input type="file" name="site_logo"
                               class="text-sm text-slate-600">
                    </div>
                </div>

                <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>


    {{-- ============================
        2. BẢO MẬT (ĐỔI MẬT KHẨU)
    ============================= --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-semibold mb-4">Bảo mật</h2>

        <form action="{{ route('admin.settings.update.security') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password"
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-slate-300">
                </div>

                <div>
                    <label class="text-sm font-medium">Mật khẩu mới</label>
                    <input type="password" name="new_password"
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-slate-300">
                </div>

                <div>
                    <label class="text-sm font-medium">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation"
                           class="w-full mt-1 px-3 py-2 rounded-xl border border-slate-300">
                </div>

                <button class="px-5 py-2.5 bg-purple-600 text-white rounded-xl hover:bg-purple-700">
                    Đổi mật khẩu
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
