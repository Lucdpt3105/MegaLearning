@extends('layouts.app')

@section('title', 'Quản lý Môn học')

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
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Quản Lý Môn Học</h1>
        <p class="text-gray-600">Tạo và quản lý các môn học của bạn</p>
    </div>

    <!-- Statistics Cards - Compact Minimalist Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Subjects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-50 rounded-lg p-2.5">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Tổng Môn Học</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subjects->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subjects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-50 rounded-lg p-2.5">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Đang Hoạt Động</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subjects->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Draft Subjects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-50 rounded-lg p-2.5">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Nháp</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subjects->where('status', 'draft')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Archived Subjects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-50 rounded-lg p-2.5">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Lưu Trữ</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subjects->where('status', 'archived')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Toolbar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Left: Search and Filter -->
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <!-- Search -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Tìm kiếm môn học..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500"
                           onkeyup="filterSubjects()">
                </div>

                <!-- Filter Dropdown -->
                <div class="relative">
                    <select id="statusFilter" 
                            onchange="filterSubjects()"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Đang hoạt động</option>
                        <option value="draft">Nháp</option>
                        <option value="archived">Lưu trữ</option>
                    </select>
                </div>
            </div>

            <!-- Right: Create Button -->
            <div>
                <a href="{{ route('teacher.subjects.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tạo Môn Học Mới
                </a>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    @if($subjects->count() > 0)
        <div id="subjectsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                // Beautiful subject images from Unsplash
                $subjectImages = [
                    'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=600&q=80', // Math/Physics
                    'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=600&q=80', // Science
                    'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80', // Literature
                    'https://images.unsplash.com/photo-1516397281156-ca07cf9746fc?w=600&q=80', // Study
                    'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&q=80', // Books
                    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80', // Learning
                ];
            @endphp
            @foreach($subjects as $index => $subject)
                <div class="subject-card bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden" 
                     data-subject-name="{{ strtolower($subject->name) }}" 
                     data-subject-code="{{ strtolower($subject->code) }}"
                     data-subject-status="{{ $subject->status }}">
                    
                    <!-- Image Container (16:9 aspect ratio) -->
                    <div class="relative aspect-video overflow-hidden bg-gray-100">
                        <a href="{{ route('teacher.subjects.show', $subject) }}" class="block w-full h-full">
                            <img src="{{ $subjectImages[$index % count($subjectImages)] }}" 
                                 alt="{{ $subject->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                        
                        <!-- Three-dot Menu -->
                        <div class="absolute top-3 right-3">
                            <div class="relative inline-block text-left">
                                <button type="button" 
                                        onclick="toggleDropdown(event, 'menu-{{ $subject->id }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white shadow-sm transition-colors">
                                    <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="menu-{{ $subject->id }}" 
                                     class="hidden absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1" role="menu">
                                        <a href="{{ route('teacher.subjects.show', $subject) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Xem Chi Tiết
                                        </a>
                                        <a href="{{ route('teacher.subjects.edit', $subject) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Chỉnh Sửa
                                        </a>
                                        <hr class="my-1">
                                        <form action="{{ route('teacher.subjects.destroy', $subject) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa môn học này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Xóa Môn Học
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area (below image) -->
                    <div class="p-4">
                        <!-- Title & Status -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 mr-2">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-1">{{ $subject->name }}</h3>
                                <p class="text-sm text-gray-500 font-mono">{{ $subject->code }}</p>
                            </div>
                            <span class="flex-shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @if($subject->status === 'active') bg-green-100 text-green-700
                                @elseif($subject->status === 'draft') bg-orange-100 text-orange-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                @if($subject->status === 'active')
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Active
                                @elseif($subject->status === 'draft')
                                    Draft
                                @else
                                    Archived
                                @endif
                            </span>
                        </div>

                        <!-- Description -->
                        @if($subject->description)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $subject->description }}</p>
                        @endif

                        <!-- Stats Grid (Compact) -->
                        <div class="grid grid-cols-4 gap-2 pt-3 border-t border-gray-100">
                            <div class="text-center">
                                <p class="text-lg font-bold text-blue-600">{{ $subject->class_rooms_count }}</p>
                                <p class="text-xs text-gray-500">Lớp</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-purple-600">{{ $subject->exams_count }}</p>
                                <p class="text-xs text-gray-500">Thi</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-green-600">{{ $subject->documents_count }}</p>
                                <p class="text-xs text-gray-500">Tài liệu</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-orange-600">{{ $subject->topics_count }}</p>
                                <p class="text-xs text-gray-500">Chủ đề</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $subjects->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có môn học nào</h3>
                <p class="text-sm text-gray-600 mb-6">Bắt đầu bằng cách tạo môn học đầu tiên của bạn!</p>
                <a href="{{ route('teacher.subjects.create') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tạo Môn Học Đầu Tiên
                </a>
            </div>
        </div>
    @endif
</div>

<script>
// Toggle dropdown menu
function toggleDropdown(event, menuId) {
    event.stopPropagation();
    const menu = document.getElementById(menuId);
    const allMenus = document.querySelectorAll('[id^="menu-"]');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== menuId) {
            m.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const allMenus = document.querySelectorAll('[id^="menu-"]');
    allMenus.forEach(menu => {
        menu.classList.add('hidden');
    });
});

// Filter subjects by search and status
function filterSubjects() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.subject-card');
    
    cards.forEach(card => {
        const name = card.dataset.subjectName;
        const code = card.dataset.subjectCode;
        const status = card.dataset.subjectStatus;
        
        const matchesSearch = name.includes(searchInput) || code.includes(searchInput);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

@endsection
