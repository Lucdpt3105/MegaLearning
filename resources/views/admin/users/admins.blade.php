@extends('admin.layout')

@section('title', 'Quản trị viên')
@section('page-title', 'Danh sách quản trị viên')
@section('page-description', 'Quản lý tài khoản quản trị MegaLearning')

@section('content')

{{-- STATS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow border border-slate-100">
        <p class="text-sm text-slate-500">Tổng quản trị viên</p>
        <h2 class="text-3xl font-semibold text-indigo-600">{{ $users->total() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow border border-slate-100">
        <p class="text-sm text-slate-500">Đang hoạt động</p>
        <h2 class="text-3xl font-semibold text-emerald-600">
            {{ $users->where('is_locked', 0)->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow border border-slate-100">
        <p class="text-sm text-slate-500">Đang bị khóa</p>
        <h2 class="text-3xl font-semibold text-red-500">
            {{ $users->where('is_locked', 1)->count() }}
        </h2>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow border border-slate-100 p-6">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Danh sách quản trị viên</h3>

        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium shadow">
            + Thêm quản trị viên
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-left">
                    <th class="px-4 py-3 rounded-l-xl">ID</th>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-right rounded-r-xl">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-4 py-3">{{ $user->id }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>

                        <td class="px-4 py-3">
                            @if($user->is_locked)
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-lg">Bị khóa</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-lg">Hoạt động</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Sửa
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                  class="inline-block ml-3"
                                  onsubmit="return confirm('Xóa quản trị viên này?');">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Xóa
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links('pagination::tailwind') }}
    </div>

</div>

@endsection
