@extends('admin.layout')

@section('title', 'Thêm danh mục khóa học')
@section('page-title', 'Thêm danh mục khóa học')
@section('page-description', 'Tạo mới một danh mục để phân loại các khóa học.')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-900">Thông tin danh mục</h2>
        <p class="text-xs text-slate-500 mt-1">
            Điền thông tin bên dưới để tạo danh mục mới.
        </p>
    </div>

    <form action="{{ route('admin.course-categories.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Tên danh mục --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tên danh mục <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Mô tả (không bắt buộc)
            </label>
            <textarea name="description" rows="4"
                      class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('admin.course-categories.index') }}"
               class="px-4 py-2 text-xs rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                Hủy
            </a>

            <button type="submit"
                    class="px-4 py-2 text-xs rounded-xl bg-indigo-600 text-white font-medium shadow-sm hover:bg-indigo-700">
                Tạo danh mục
            </button>
        </div>
    </form>

</div>

@endsection
