@extends('layouts.app')

@section('title', 'Bài Kiểm Tra - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Bài Kiểm Tra</h1>
        <p class="text-gray-600">Danh sách các bài kiểm tra của bạn</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="#" onclick="filterExams('all')" class="tab-link active border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                Tất Cả
            </a>
            <a href="#" onclick="filterExams('ongoing')" class="tab-link border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Đang Diễn Ra
            </a>
            <a href="#" onclick="filterExams('upcoming')" class="tab-link border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Sắp Tới
            </a>
            <a href="#" onclick="filterExams('finished')" class="tab-link border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Đã Kết Thúc
            </a>
        </nav>
    </div>

    <!-- Exam List -->
    @if($exams->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài kiểm tra</h3>
            <p class="mt-1 text-sm text-gray-500">Giáo viên chưa giao bài kiểm tra nào.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($exams as $exam)
                <div class="exam-card bg-white rounded-lg shadow hover:shadow-md transition-shadow p-6 
                    {{ $exam->is_ongoing ? 'border-l-4 border-green-500' : '' }}
                    {{ $exam->is_upcoming ? 'border-l-4 border-blue-500' : '' }}
                    {{ $exam->is_finished ? 'border-l-4 border-gray-400' : '' }}"
                    data-status="{{ $exam->is_ongoing ? 'ongoing' : ($exam->is_upcoming ? 'upcoming' : 'finished') }}">
                    
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $exam->title }}</h3>
                                
                                @if($exam->is_ongoing)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Đang Diễn Ra</span>
                                @elseif($exam->is_upcoming)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Sắp Tới</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Đã Kết Thúc</span>
                                @endif

                                @if($exam->require_access_code)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">🔒 Có Mã</span>
                                @endif
                            </div>

                            <p class="text-gray-600 mb-3">{{ $exam->description }}</p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Môn học:</span>
                                    <span class="font-medium text-gray-900 ml-1">{{ $exam->subject ? $exam->subject->name : 'Chưa có môn học' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Lớp:</span>
                                    <span class="font-medium text-gray-900 ml-1">{{ $exam->classRoom->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Thời gian:</span>
                                    <span class="font-medium text-gray-900 ml-1">{{ $exam->duration }} phút</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Điểm tối đa:</span>
                                    <span class="font-medium text-gray-900 ml-1">{{ $exam->total_points }}</span>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center gap-4 text-sm">
                                @if($exam->start_time && $exam->end_time)
                                    <span class="text-gray-500">
                                        ⏰ {{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-gray-500">
                                        ⏰ Không giới hạn thời gian
                                    </span>
                                @endif
                                @if($exam->submission_count > 0)
                                    <span class="text-blue-600">
                                        📝 Đã làm: {{ $exam->submission_count }} lần
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="ml-4 flex flex-col gap-2">
                            @if($exam->can_take)
                                <a href="{{ route('student.exams.show', $exam->id) }}" 
                                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-center font-medium">
                                    Làm Bài
                                </a>
                            @else
                                <a href="{{ route('student.exams.show', $exam->id) }}" 
                                   class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                                    Xem Chi Tiết
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function filterExams(status) {
    const cards = document.querySelectorAll('.exam-card');
    const tabs = document.querySelectorAll('.tab-link');
    
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
