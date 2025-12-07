@extends('layouts.app')

@section('title', 'Nhập Mã Truy Cập - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-md">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Yêu Cầu Mã Truy Cập</h1>
            <p class="text-gray-600">{{ $exam->title }}</p>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.exams.take', $exam->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="access_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Mã Truy Cập
                </label>
                <input type="text" 
                       id="access_code" 
                       name="access_code" 
                       required
                       class="w-full px-4 py-3 text-lg text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent tracking-widest"
                       placeholder="Nhập mã truy cập"
                       autocomplete="off">
                @error('access_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    💡 <strong>Lưu ý:</strong> Mã truy cập do giáo viên cung cấp. Vui lòng liên hệ giáo viên nếu bạn chưa có mã.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('student.exams.show', $exam->id) }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center font-medium">
                    Hủy
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Xác Nhận
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
