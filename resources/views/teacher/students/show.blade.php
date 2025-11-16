@extends('layouts.app')

@section('title', 'Quản lý Học sinh - ' . $classRoom->name)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.students.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $classRoom->name }}</h1>
                <p class="text-gray-600 mt-1">UC-GV-054: Xem và quản lý danh sách học sinh</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Class Info -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-md">
            <p class="text-gray-600 text-sm">Môn học</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $classRoom->subject->name }}</p>
            <p class="text-xs text-gray-500">{{ $classRoom->subject->code }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <p class="text-gray-600 text-sm">Số học sinh</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $classRoom->students->count() }}</p>
            @if($classRoom->max_students)
            <p class="text-xs text-gray-500">Tối đa: {{ $classRoom->max_students }}</p>
            @endif
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <p class="text-gray-600 text-sm">Trạng thái</p>
            <p class="text-sm font-bold mt-1 {{ $classRoom->status === 'active' ? 'text-green-600' : 'text-gray-600' }}">
                {{ $classRoom->status === 'active' ? '✅ Hoạt động' : '❌ Đã đóng' }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <p class="text-gray-600 text-sm">Thời gian</p>
            <p class="text-sm font-medium text-gray-900 mt-1">
                {{ $classRoom->start_date ? $classRoom->start_date->format('d/m/Y') : 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl p-6 shadow-md mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Thao tác nhanh</h2>
        <div class="flex flex-wrap gap-3">
            <button onclick="openAddStudentModal()" class="inline-flex items-center space-x-2 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Thêm học sinh (UC-GV-051)</span>
            </button>

            <a href="{{ route('teacher.subjects.show', $classRoom->subject) }}" class="inline-flex items-center space-x-2 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Xem môn học</span>
            </a>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-900">Danh sách học sinh ({{ $classRoom->students->count() }})</h2>
                
                <!-- Search and Filter -->
                <div class="flex gap-3">
                    <input 
                        type="text" 
                        id="searchStudent" 
                        placeholder="Tìm kiếm theo tên, email, mã HS..." 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-64"
                        onkeyup="filterStudentList()"
                    >
                    <select 
                        id="filterGender" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        onchange="filterStudentList()"
                    >
                        <option value="">Tất cả giới tính</option>
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
            </div>
        </div>

        @if($classRoom->students->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học sinh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã HS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email / SĐT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giới tính</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ghi chú</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="studentTableBody">
                    @foreach($classRoom->students as $student)
                    <tr class="hover:bg-gray-50 student-row" 
                        data-name="{{ strtolower($student->name) }}" 
                        data-email="{{ strtolower($student->email) }}" 
                        data-student-id="{{ strtolower($student->student_id ?? '') }}"
                        data-gender="{{ $student->gender ?? '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($student->avatar)
                                <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="shrink-0 h-10 w-10 rounded-full object-cover">
                                @else
                                <div class="shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                    @if($student->date_of_birth)
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->student_id ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->email }}</div>
                            @if($student->phone)
                            <div class="text-xs text-gray-500">{{ $student->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($student->gender === 'male')
                            <span class="text-sm text-gray-700">👨 Nam</span>
                            @elseif($student->gender === 'female')
                            <span class="text-sm text-gray-700">👩 Nữ</span>
                            @elseif($student->gender === 'other')
                            <span class="text-sm text-gray-700">⚧ Khác</span>
                            @else
                            <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                ✓ Đang học
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate">
                                {{ $student->pivot->notes ?? 'Chưa có ghi chú' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button onclick='openEditStudentModal(@json($student))' class="text-blue-600 hover:text-blue-900" title="Cập nhật thông tin học sinh">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </button>
                                <button onclick="openEditNotesModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ addslashes($student->pivot->notes ?? '') }}')" class="text-indigo-600 hover:text-indigo-900" title="Sửa ghi chú (UC-GV-053)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="removeStudent({{ $student->id }})" class="text-red-600 hover:text-red-900" title="Xóa khỏi lớp (UC-GV-052)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-gray-500 mb-4">Chưa có học sinh nào trong lớp</p>
            <button onclick="openAddStudentModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Thêm học sinh đầu tiên
            </button>
        </div>
        @endif
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Thêm học sinh vào lớp</h3>
                <button onclick="closeAddStudentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="addStudentForm" action="{{ route('teacher.students.add', $classRoom) }}" method="POST">
                @csrf
                
                @if($availableStudents->count() > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Chọn học sinh ({{ $availableStudents->count() }} học sinh có sẵn)
                        </label>
                        
                        <!-- Search box -->
                        <div class="mb-3">
                            <input 
                                type="text" 
                                id="studentSearch" 
                                placeholder="Tìm kiếm theo tên hoặc email..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                onkeyup="filterStudents()"
                            >
                        </div>

                        <!-- Select All -->
                        <div class="mb-3 flex items-center">
                            <input 
                                type="checkbox" 
                                id="selectAll" 
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                onchange="toggleSelectAll()"
                            >
                            <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">
                                Chọn tất cả
                            </label>
                        </div>

                        <!-- Students list -->
                        <div class="border border-gray-200 rounded-lg max-h-96 overflow-y-auto">
                            @foreach($availableStudents as $student)
                            <div class="student-item flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
                                 data-name="{{ strtolower($student->name) }}" 
                                 data-email="{{ strtolower($student->email) }}">
                                <input 
                                    type="checkbox" 
                                    name="student_ids[]" 
                                    value="{{ $student->id }}" 
                                    id="student_{{ $student->id }}"
                                    class="student-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                                <label for="student_{{ $student->id }}" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="shrink-0 h-10 w-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            onclick="closeAddStudentModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                        >
                            Hủy
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                        >
                            Thêm học sinh
                        </button>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-gray-500 mb-4">Tất cả học sinh đã được thêm vào lớp</p>
                        <button 
                            type="button" 
                            onclick="closeAddStudentModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                        >
                            Đóng
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Edit Enrollment Modal -->
    <div id="editEnrollmentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Cập nhật thông tin học sinh</h3>
                <button onclick="closeEditEnrollmentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editEnrollmentForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Học sinh: <span id="enrollmentStudentNameDisplay" class="font-bold"></span>
                    </label>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Trạng thái
                    </label>
                    <select 
                        name="status" 
                        id="enrollmentStatusSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="active">✓ Đang học</option>
                        <option value="dropped">✗ Đã rời lớp</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ghi chú
                    </label>
                    <textarea 
                        name="notes" 
                        id="enrollmentNotesTextarea"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Nhập ghi chú về học sinh..."
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeEditEnrollmentModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Hủy
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Student Info Modal -->
    <div id="editStudentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-5 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white my-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-900">Cập nhật thông tin học sinh</h3>
                <button onclick="closeEditStudentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editStudentForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        1. Thông tin cá nhân
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="studentName"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Nhập họ và tên học sinh"
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mã học sinh / ID
                                </label>
                                <input 
                                    type="text" 
                                    name="student_id" 
                                    id="studentIdInput"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="VD: HS2025001"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Giới tính
                                </label>
                                <select 
                                    name="gender" 
                                    id="studentGender"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                    <option value="">Chọn giới tính</option>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ngày sinh
                                </label>
                                <input 
                                    type="date" 
                                    name="date_of_birth" 
                                    id="studentDOB"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ảnh đại diện (tuỳ chọn)
                                </label>
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="studentAvatar"
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                >
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG tối đa 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        2. Thông tin liên hệ
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="studentPhone"
                                    required
                                    pattern="[0-9]{10,11}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="VD: 0912345678"
                                >
                                <p class="text-xs text-gray-500 mt-1">10-11 chữ số</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="studentEmail"
                                    readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                >
                                <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Địa chỉ (tuỳ chọn)
                            </label>
                            <textarea 
                                name="address" 
                                id="studentAddress"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Nhập địa chỉ học sinh"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button 
                        type="button" 
                        onclick="closeEditStudentModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Hủy
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                    >
                        Lưu & Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Notes Modal -->
    <div id="editNotesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Cập nhật ghi chú</h3>
                <button onclick="closeEditNotesModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editNotesForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Học sinh: <span id="studentNameDisplay" class="font-bold"></span>
                    </label>
                    <textarea 
                        name="notes" 
                        id="notesTextarea"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Nhập ghi chú về học sinh..."
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeEditNotesModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                    >
                        Hủy
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                    >
                        Lưu ghi chú
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filter Student List (in table)
function filterStudentList() {
    const searchInput = document.getElementById('searchStudent').value.toLowerCase();
    const genderFilter = document.getElementById('filterGender').value;
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        const studentId = row.dataset.studentId;
        const gender = row.dataset.gender;
        
        const matchesSearch = name.includes(searchInput) || email.includes(searchInput) || studentId.includes(searchInput);
        const matchesGender = !genderFilter || gender === genderFilter;
        
        if (matchesSearch && matchesGender) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Edit Student Modal
function openEditStudentModal(student) {
    document.getElementById('editStudentModal').classList.remove('hidden');
    document.getElementById('studentName').value = student.name || '';
    document.getElementById('studentIdInput').value = student.student_id || '';
    document.getElementById('studentGender').value = student.gender || '';
    document.getElementById('studentDOB').value = student.date_of_birth || '';
    document.getElementById('studentPhone').value = student.phone || '';
    document.getElementById('studentEmail').value = student.email || '';
    document.getElementById('studentAddress').value = student.address || '';
    document.getElementById('editStudentForm').action = '{{ route("teacher.students.update-info", ["classRoom" => $classRoom->id, "studentId" => "__STUDENT_ID__"]) }}'.replace('__STUDENT_ID__', student.id);
}

function closeEditStudentModal() {
    document.getElementById('editStudentModal').classList.add('hidden');
}

// Add Student Modal
function openAddStudentModal() {
    document.getElementById('addStudentModal').classList.remove('hidden');
}

function closeAddStudentModal() {
    document.getElementById('addStudentModal').classList.add('hidden');
    document.getElementById('addStudentForm').reset();
    document.getElementById('selectAll').checked = false;
}

function filterStudents() {
    const searchInput = document.getElementById('studentSearch');
    const filter = searchInput.value.toLowerCase();
    const studentItems = document.querySelectorAll('.student-item');
    
    studentItems.forEach(item => {
        const name = item.dataset.name;
        const email = item.dataset.email;
        
        if (name.includes(filter) || email.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    const visibleCheckboxes = Array.from(checkboxes).filter(cb => 
        cb.closest('.student-item').style.display !== 'none'
    );
    
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Edit Enrollment Modal
function openEditEnrollmentModal(studentId, studentName, status, notes) {
    document.getElementById('editEnrollmentModal').classList.remove('hidden');
    document.getElementById('enrollmentStudentNameDisplay').textContent = studentName;
    document.getElementById('enrollmentStatusSelect').value = status;
    document.getElementById('enrollmentNotesTextarea').value = notes;
    document.getElementById('editEnrollmentForm').action = '{{ route("teacher.students.update-enrollment", ["classRoom" => $classRoom->id, "studentId" => "__STUDENT_ID__"]) }}'.replace('__STUDENT_ID__', studentId);
}

function closeEditEnrollmentModal() {
    document.getElementById('editEnrollmentModal').classList.add('hidden');
}

// Edit Notes Modal
function openEditNotesModal(studentId, studentName, notes) {
    document.getElementById('editNotesModal').classList.remove('hidden');
    document.getElementById('studentNameDisplay').textContent = studentName;
    document.getElementById('notesTextarea').value = notes;
    document.getElementById('editNotesForm').action = '{{ route("teacher.students.update-notes", ["classRoom" => $classRoom->id, "studentId" => "__STUDENT_ID__"]) }}'.replace('__STUDENT_ID__', studentId);
}

function closeEditNotesModal() {
    document.getElementById('editNotesModal').classList.add('hidden');
}

// Remove Student
function removeStudent(studentId) {
    if (confirm('Bạn có chắc chắn muốn xóa học sinh này khỏi lớp?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("teacher.students.remove", ["classRoom" => $classRoom->id, "studentId" => "__STUDENT_ID__"]) }}'.replace('__STUDENT_ID__', studentId);
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals on outside click
window.onclick = function(event) {
    const addModal = document.getElementById('addStudentModal');
    const editModal = document.getElementById('editNotesModal');
    const enrollmentModal = document.getElementById('editEnrollmentModal');
    const studentModal = document.getElementById('editStudentModal');
    if (event.target == addModal) {
        closeAddStudentModal();
    }
    if (event.target == editModal) {
        closeEditNotesModal();
    }
    if (event.target == enrollmentModal) {
        closeEditEnrollmentModal();
    }
    if (event.target == studentModal) {
        closeEditStudentModal();
    }
}
</script>
@endsection
