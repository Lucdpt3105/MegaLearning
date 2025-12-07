@extends('layouts.app')

@section('title', 'Quản lý Nhóm Chat - ' . $subject->name)

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.subjects.show', $subject) }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Quản lý Nhóm Chat</h1>
                <p class="text-gray-600 mt-1">{{ $subject->name }} ({{ $subject->code }})</p>
            </div>
        </div>
    </div>

    <!-- Chat Room Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Thành viên</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $chatRoom->members->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center space-x-3">
                <div class="bg-green-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tin nhắn</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $chatRoom->messages->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center space-x-3">
                <div class="bg-purple-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Trạng thái</p>
                    <p class="text-sm font-bold {{ $chatRoom->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $chatRoom->is_active ? '✅ Hoạt động' : '❌ Đã đóng' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl p-6 shadow-md mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Thao tác nhanh</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ url('/chat') }}?room={{ $chatRoom->id }}" target="_blank" class="inline-flex items-center space-x-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Mở Chat Room</span>
            </a>

            <button onclick="openAddMemberModal()" class="inline-flex items-center space-x-2 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Thêm thành viên</span>
            </button>

            <form action="{{ route('teacher.subjects.chat-room.toggle', $subject) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center space-x-2 {{ $chatRoom->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($chatRoom->is_active)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        @endif
                    </svg>
                    <span>{{ $chatRoom->is_active ? 'Đóng phòng chat' : 'Mở phòng chat' }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Danh sách thành viên ({{ $chatRoom->members->count() }})</h2>
        </div>
        
        @if($chatRoom->members->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành viên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tham gia</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chatRoom->members as $member)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $member->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $member->pivot->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $member->pivot->role === 'admin' ? '👑 Admin' : '👤 Member' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($member->pivot->role !== 'admin')
                            <button onclick="removeMember({{ $member->id }})" class="text-red-600 hover:text-red-900 font-medium">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Xóa
                            </button>
                            @else
                            <span class="text-gray-400 text-sm">Quản trị viên</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-gray-500">Chưa có thành viên nào trong nhóm chat</p>
        </div>
        @endif
    </div>

    <!-- Add Member Modal -->
    <div id="addMemberModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Thêm thành viên vào nhóm chat</h3>
                <button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="addMemberForm" action="{{ route('teacher.subjects.chat-room.add-member', $subject) }}" method="POST">
                @csrf
                
                @if($availableUsers->count() > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Chọn người dùng ({{ $availableUsers->count() }} người có sẵn)
                        </label>
                        
                        <!-- Search box -->
                        <div class="mb-3">
                            <input 
                                type="text" 
                                id="memberSearch" 
                                placeholder="Tìm kiếm theo tên, email hoặc vai trò..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                onkeyup="filterMembers()"
                            >
                        </div>

                        <!-- Filter by role -->
                        <div class="mb-3 flex space-x-2">
                            <button type="button" onclick="filterByRole('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">
                                Tất cả ({{ $availableUsers->count() }})
                            </button>
                            <button type="button" onclick="filterByRole('teacher')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700">
                                Giáo viên ({{ $availableUsers->where('role', 'teacher')->count() }})
                            </button>
                            <button type="button" onclick="filterByRole('student')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700">
                                Học sinh ({{ $availableUsers->where('role', 'student')->count() }})
                            </button>
                        </div>

                        <!-- Select All -->
                        <div class="mb-3 flex items-center">
                            <input 
                                type="checkbox" 
                                id="selectAll" 
                                class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                onchange="toggleSelectAll()"
                            >
                            <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">
                                Chọn tất cả
                            </label>
                        </div>

                        <!-- Members list -->
                        <div class="border border-gray-200 rounded-lg max-h-96 overflow-y-auto">
                            @foreach($availableUsers as $user)
                            <div class="member-item flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
                                 data-name="{{ strtolower($user->name) }}" 
                                 data-email="{{ strtolower($user->email) }}"
                                 data-role="{{ $user->role }}">
                                <input 
                                    type="checkbox" 
                                    name="user_ids[]" 
                                    value="{{ $user->id }}" 
                                    id="user_{{ $user->id }}"
                                    class="member-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                >
                                <label for="user_{{ $user->id }}" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @php
                                                $colorClass = $user->role === 'teacher' 
                                                    ? 'bg-gradient-to-r from-orange-500 to-red-500' 
                                                    : 'bg-gradient-to-r from-blue-500 to-cyan-500';
                                            @endphp
                                            <div class="flex-shrink-0 h-10 w-10 {{ $colorClass }} rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'teacher' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $user->role === 'teacher' ? '👨‍🏫 Giáo viên' : '👨‍🎓 Học sinh' }}
                                        </span>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            onclick="closeAddMemberModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                        >
                            Hủy
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium"
                        >
                            Thêm thành viên
                        </button>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-gray-500 mb-4">Tất cả người dùng đã được thêm vào nhóm chat</p>
                        <button 
                            type="button" 
                            onclick="closeAddMemberModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
                        >
                            Đóng
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
// Modal controls
function openAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
    // Reset form
    document.getElementById('addMemberForm').reset();
    document.getElementById('selectAll').checked = false;
    // Reset filter buttons
    filterByRole('all');
}

// Filter members by search
function filterMembers() {
    const searchInput = document.getElementById('memberSearch');
    const filter = searchInput.value.toLowerCase();
    const memberItems = document.querySelectorAll('.member-item');
    
    memberItems.forEach(item => {
        const name = item.dataset.name;
        const email = item.dataset.email;
        const role = item.dataset.role;
        
        if (name.includes(filter) || email.includes(filter) || role.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    updateSelectAllState();
}

// Filter by role
let currentRoleFilter = 'all';
function filterByRole(role) {
    currentRoleFilter = role;
    const memberItems = document.querySelectorAll('.member-item');
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    filterBtns.forEach(btn => {
        btn.classList.remove('bg-purple-600', 'text-white', 'active');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('bg-purple-600', 'text-white', 'active');
    
    // Filter items
    memberItems.forEach(item => {
        if (role === 'all') {
            item.style.display = '';
        } else if (item.dataset.role === role) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Clear search
    document.getElementById('memberSearch').value = '';
    updateSelectAllState();
}

// Select all toggle
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const visibleCheckboxes = Array.from(checkboxes).filter(cb => 
        cb.closest('.member-item').style.display !== 'none'
    );
    
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Update select all state based on visible items
function updateSelectAllState() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const visibleCheckboxes = Array.from(checkboxes).filter(cb => 
        cb.closest('.member-item').style.display !== 'none'
    );
    
    if (visibleCheckboxes.length === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    } else {
        const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
        selectAll.checked = checkedCount === visibleCheckboxes.length;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addMemberModal');
    if (event.target == modal) {
        closeAddMemberModal();
    }
}

function removeMember(memberId) {
    if (confirm('Bạn có chắc chắn muốn xóa thành viên này khỏi nhóm chat?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("teacher.subjects.chat-room.remove-member", ["subject" => $subject->id, "userId" => "__MEMBER_ID__"]) }}'.replace('__MEMBER_ID__', memberId);
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Update checkbox state on change
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.member-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllState);
    });
});
</script>
@endsection
