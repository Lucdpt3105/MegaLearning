@extends('layouts.teacher')

@section('title', 'Chi tiết Danh mục - ' . $category->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white py-4">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h2 class="fw-bold mb-2">{{ $category->name }}</h2>
                    <p class="mb-0 opacity-90">{{ $category->description }}</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                        {{ $category->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                    </span>
                    <a href="{{ route('teacher.categories.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">ID Danh mục</p>
                            <h4 class="mb-0 fw-bold">{{ $category->id }}</h4>
                        </div>
                        <i class="bi bi-hash text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Tổng khóa học</p>
                            <h4 class="mb-0 fw-bold">{{ $category->courses->count() }}</h4>
                        </div>
                        <i class="bi bi-book text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Ngày tạo</p>
                            <h6 class="mb-0 fw-bold">{{ $category->created_at->format('d/m/Y') }}</h6>
                            <small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                        </div>
                        <i class="bi bi-calendar-plus text-info fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Cập nhật</p>
                            <h6 class="mb-0 fw-bold">{{ $category->updated_at->format('d/m/Y') }}</h6>
                            <small class="text-muted">{{ $category->updated_at->diffForHumans() }}</small>
                        </div>
                        <i class="bi bi-calendar-check text-warning fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slug Info -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="text-muted mb-2"><i class="bi bi-link-45deg me-2"></i>URL Slug</h6>
            <code class="d-block p-3 bg-light rounded">{{ $category->slug }}</code>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-book me-2"></i>Khóa học của bạn trong danh mục này</h5>
        </div>
        <div class="card-body p-0">
            @if($category->courses->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Danh mục này chưa có khóa học nào</h5>
                    <p class="text-muted">Bạn chưa tạo khóa học nào trong danh mục này</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã khóa học</th>
                                <th>Tên khóa học</th>
                                <th>Giáo viên</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->courses as $course)
                                <tr>
                                    <td><code>{{ $course->course_code }}</code></td>
                                    <td>
                                        <strong>{{ $course->name }}</strong>
                                        @if($course->description)
                                            <br><small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($course->status === 'active')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @elseif($course->status === 'closed')
                                            <span class="badge bg-danger">Đã đóng</span>
                                        @else
                                            <span class="badge bg-secondary">Lưu trữ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $course->start_date ? $course->start_date->format('d/m/Y') : 'N/A' }}
                                            @if($course->end_date)
                                                - {{ $course->end_date->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
