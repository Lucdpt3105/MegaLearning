@extends('layouts.teacher')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Danh mục</h2>
            <p class="text-muted mb-0">Xem thông tin các danh mục khóa học</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Tổng danh mục</p>
                            <h3 class="mb-0 fw-bold">{{ $categories->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-folder fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Đang hoạt động</p>
                            <h3 class="mb-0 fw-bold">{{ $categories->where('is_active', 1)->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Tổng khóa học</p>
                            <h3 class="mb-0 fw-bold">{{ $categories->sum('courses_count') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-book fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex">
            <i class="bi bi-info-circle fs-5 me-3"></i>
            <div>
                <h6 class="alert-heading mb-1">Lưu ý</h6>
                <p class="mb-0 small">Danh mục được quản lý bởi Admin. Bạn có thể xem thông tin danh mục và các khóa học liên quan.</p>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    @if($categories->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Chưa có danh mục nào</h4>
            <p class="text-muted">Danh mục sẽ được quản lý bởi Admin</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                        <div class="card-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1 fw-bold">{{ $category->name }}</h5>
                                    <p class="mb-0 small opacity-90">{{ Str::limit($category->description, 60) }}</p>
                                </div>
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }} ms-2">
                                    {{ $category->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <i class="bi bi-book text-primary fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">Khóa học</p>
                                        <h5 class="mb-0 fw-bold">{{ $category->courses_count }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <i class="bi bi-hash text-info fs-4"></i>
                                        <p class="mb-0 mt-2 small text-muted">ID</p>
                                        <h5 class="mb-0 fw-bold">{{ $category->id }}</h5>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="small text-muted">Slug</label>
                                <code class="d-block p-2 bg-light rounded">{{ $category->slug }}</code>
                            </div>

                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="bi bi-calendar3 me-2"></i>
                                <span>{{ $category->created_at->format('d/m/Y') }}</span>
                            </div>

                            <a href="{{ route('teacher.categories.show', $category) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye me-2"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.transition {
    transition: all 0.3s ease;
}
</style>
@endsection
