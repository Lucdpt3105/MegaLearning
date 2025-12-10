@extends('admin.layout')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Chi tiết danh mục</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Chỉnh sửa
            </a>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-lg text-gray-900">{{ $category->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Trạng thái</h3>
                <p class="text-lg">
                    @if($category->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Kích hoạt</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Không kích hoạt</span>
                    @endif
                </p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Tên danh mục</h3>
                <p class="text-lg text-gray-900">{{ $category->name }}</p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Slug</h3>
                <p class="text-lg text-gray-900">{{ $category->slug }}</p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Mô tả</h3>
                <p class="text-lg text-gray-900">{{ $category->description ?? 'Không có mô tả' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Số khóa học</h3>
                <p class="text-lg text-gray-900">{{ $category->courses_count }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Ngày tạo</h3>
                <p class="text-lg text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Cập nhật lần cuối</h3>
                <p class="text-lg text-gray-900">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t">
            <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" 
                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Xóa danh mục
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
