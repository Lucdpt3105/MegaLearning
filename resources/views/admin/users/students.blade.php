@extends('admin.layout')

@section('title', 'Quản lý học sinh')
@section('page-title', 'Danh sách học sinh')
@section('page-description', 'Quản lý tất cả tài khoản học sinh hệ thống')

@section('content')

{{-- STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow border border-slate-100">
        <p class="text-sm text-slate-500">Tổng học sinh</p>
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
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Danh sách học sinh</h3>

        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium shadow">
            + Thêm học sinh
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-left">
                    <th class="px-4 py-3 rounded-l-xl">STT</th>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3 text-right rounded-r-xl">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3 font-medium text-slate-800">
                            {{ $user->name }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            @if($user->is_locked)
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-lg">Bị khóa</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-lg">Hoạt động</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- Xem --}}
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                   title="Xem chi tiết">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>

                                {{-- Sửa --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition"
                                   title="Chỉnh sửa">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </a>

                                {{-- Khóa/Mở khóa --}}
                                <form action="{{ route('admin.users.toggle-lock', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="p-2 {{ $user->is_locked ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-amber-50 text-amber-600 hover:bg-amber-100' }} rounded-lg transition"
                                            title="{{ $user->is_locked ? 'Mở khóa' : 'Khóa tài khoản' }}"
                                            onclick="return confirm('{{ $user->is_locked ? 'Mở khóa tài khoản này?' : 'Khóa tài khoản này?' }}')">
                                        <i data-feather="{{ $user->is_locked ? 'unlock' : 'lock' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                {{-- Xóa --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Xóa học sinh này? Hành động không thể hoàn tác!');"
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                                            title="Xóa">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links('pagination::tailwind') }}
    </div>

</div>

@endsection
