@extends('admin.layout')

@section('title', 'Chỉnh sửa danh mục')
@section('page-title', 'Chỉnh sửa danh mục')
@section('page-description', 'Cập nhật thông tin danh mục khóa học.')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-900">Thông tin danh mục</h2>
        <p class="text-xs text-slate-500 mt-1">
            Chỉnh sửa nội dung bên dưới để cập nhật danh mục.
        </p>
    </div>

    <form action="{{ route('admin.course-categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tên danh mục
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Mô tả
            </label>
            <textarea name="description" rows="4"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('admin.course-categories.index') }}"
               class="px-4 py-2 text-xs rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                Hủy
            </a>

            <button type="submit"
                    class="px-4 py-2 text-xs rounded-xl bg-indigo-600 text-white font-medium shadow-sm hover:bg-indigo-700">
                Lưu thay đổi
            </button>
        </div>

    </form>
</div>

@endsection
