@extends('admin.layout')

@section('title', 'Thêm khóa học')
@section('page-title', 'Thêm khóa học mới')
@section('page-description', 'Tạo khóa học mới và gán vào môn học (subject) có sẵn.')

@section('content')
<form action="{{ route('admin.courses.store') }}" method="POST"
      class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    {{-- CỘT TRÁI --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Thông tin cơ bản --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="ri-information-line text-indigo-500 text-lg"></i>
                <h2 class="text-sm font-semibold text-slate-900">Thông tin cơ bản</h2>
            </div>

            <div class="p-5 space-y-4">

                {{-- Tên khóa học --}}
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Tên khóa học <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2"
                           placeholder="VD: Toán Đại Cương, Python cơ bản">
                </div>

                {{-- Mô tả --}}
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Mô tả khóa học
                    </label>
                    <textarea name="description" rows="5"
                              class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2"
                              placeholder="Mô tả nội dung khóa học">{{ old('description') }}</textarea>
                </div>

                {{-- Môn học + Giáo viên --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Subject --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Môn học <span class="text-rose-500">*</span>
                        </label>
                        <select name="subject_id"
                                class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2">
                            <option value="">-- Chọn môn học --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    @selected(old('subject_id', $selectedSubjectId ?? null) == $subject->id)>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Giáo viên --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Giáo viên phụ trách <span class="text-rose-500">*</span>
                        </label>
                        <select name="teacher_id"
                                class="w-full rounded-xl border border-slate-200 text-sm px-3 py-2">
                            <option value="">-- Chọn giáo viên --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    @selected(old('teacher_id') == $teacher->id)>
                                    {{ $teacher->name }} ({{ $teacher->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sĩ số + ngày tháng --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Số học viên tối đa *</label>
                        <input type="number" name="max_students"
                               value="{{ old('max_students', 50) }}"
                               min="1"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Ngày bắt đầu *</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Ngày kết thúc</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                </div>

            </div>
        </div>

    </div>

    {{-- CỘT PHẢI --}}
    <div class="space-y-6">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">Trạng thái</h2>
            </div>
            <div class="p-5 text-sm space-y-2">
                <p class="text-xs text-slate-500">
                    Khi tạo mới, khóa học sẽ tự động đặt trạng thái:
                    <span class="text-emerald-600 font-semibold">active</span>.
                </p>
            </div>
        </div>

        {{-- Nút lưu --}}
        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full rounded-xl bg-indigo-600 text-white text-sm font-semibold px-4 py-2 hover:bg-indigo-700">
                <i class="ri-save-line mr-1"></i> Lưu khóa học
            </button>

            <a href="{{ route('admin.courses.index') }}"
               class="w-full rounded-xl border border-slate-200 text-xs text-slate-600 px-4 py-2 text-center hover:bg-slate-50">
                Hủy và quay lại danh sách
            </a>
        </div>

    </div>

</form>
@endsection
