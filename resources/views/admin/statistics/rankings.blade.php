@extends('admin.layout')

@section('title', 'Xếp hạng Học sinh')
@section('page-title', '🏆 Xếp hạng Học sinh')
@section('page-description', 'Bảng xếp hạng học sinh theo điểm số và thành tích học tập')

@push('styles')
<style>
    .rank-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .rank-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .medal {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .medal-gold {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #854d0e;
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
    }
    .medal-silver {
        background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
        color: #4b5563;
        box-shadow: 0 4px 12px rgba(192, 192, 192, 0.4);
    }
    .medal-bronze {
        background: linear-gradient(135deg, #cd7f32 0%, #e6a963 100%);
        color: #451a03;
        box-shadow: 0 4px 12px rgba(205, 127, 50, 0.4);
    }
    .filter-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
</style>
@endpush

@section('content')

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm mb-1">Tổng học sinh</p>
                <h3 class="text-3xl font-bold">{{ number_format($rankings->total()) }}</h3>
            </div>
            <div class="text-4xl opacity-80">🎓</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">GPA trung bình</p>
                <h3 class="text-3xl font-bold">{{ $rankings->count() > 0 ? number_format($rankings->avg('gpa'), 2) : '0.00' }}</h3>
            </div>
            <div class="text-4xl opacity-80">📊</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">GPA cao nhất</p>
                <h3 class="text-3xl font-bold">{{ $rankings->count() > 0 ? number_format($rankings->max('gpa'), 2) : '0.00' }}</h3>
            </div>
            <div class="text-4xl opacity-80">⭐</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Lớp học</p>
                <h3 class="text-3xl font-bold">{{ $classRooms->count() }}</h3>
            </div>
            <div class="text-4xl opacity-80">🏫</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Bộ lọc & Tính toán</h3>
        <button onclick="recalculateRankings()" id="recalculateBtn" 
                class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition shadow-md">
            <i data-feather="refresh-cw" class="w-4 h-4 inline mr-1"></i> Tính lại xếp hạng
        </button>
    </div>
    
    <form action="{{ route('admin.statistics.rankings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i data-feather="book" class="w-4 h-4 inline text-blue-600"></i> Lớp học
            </label>
            <select name="class_room_id" id="filterClassRoom" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tất cả lớp</option>
                @foreach($classRooms as $class)
                    <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i data-feather="layers" class="w-4 h-4 inline text-green-600"></i> Môn học
            </label>
            <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tất cả môn</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.statistics.rankings') }}" 
               class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4"></i>
            </a>
        </div>
    </form>
</div>

<!-- Top 3 Students -->
@if($rankings->count() >= 3)
<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i data-feather="award" class="w-5 h-5 inline text-yellow-600"></i>
        Top 3 Học sinh Xuất sắc
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($rankings->take(3) as $index => $ranking)
        <div class="rank-card text-center">
            <div class="flex justify-center mb-4">
                <div class="medal 
                    {{ $index == 0 ? 'medal-gold' : ($index == 1 ? 'medal-silver' : 'medal-bronze') }}">
                    {{ $index + 1 }}
                </div>
            </div>
            
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center text-3xl font-bold">
                {{ substr($ranking->student->name ?? 'N/A', 0, 1) }}
            </div>
            
            <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $ranking->student->name ?? 'N/A' }}</h4>
            <p class="text-sm text-gray-500 mb-3">{{ $ranking->student->email ?? 'N/A' }}</p>
            
            <div class="space-y-2">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-600">GPA</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($ranking->gpa, 2) }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-blue-50 rounded-lg p-2">
                        <p class="text-xs text-gray-600">Điểm TB</p>
                        <p class="font-bold text-blue-600">{{ number_format($ranking->average_score, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-2">
                        <p class="text-xs text-gray-600">Bài thi</p>
                        <p class="font-bold text-purple-600">{{ $ranking->total_exams_taken }}</p>
                    </div>
                </div>
                
                @if($ranking->classRoom)
                <p class="text-xs text-gray-500 mt-2">
                    <i data-feather="book" class="w-3 h-3 inline"></i>
                    {{ $ranking->classRoom->name }}
                </p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Full Rankings Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
        <h3 class="text-lg font-bold text-gray-800">
            <i data-feather="list" class="w-5 h-5 inline text-orange-600"></i>
            Bảng xếp hạng đầy đủ
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Hạng</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Học sinh</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Lớp học</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Môn học</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">GPA</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Điểm TB</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Bài thi</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Đỗ</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Điểm danh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rankings as $index => $ranking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-white
                            {{ $ranking->rank <= 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 
                               ($ranking->rank <= 10 ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gray-400') }}">
                            {{ $ranking->rank ?? ($rankings->firstItem() + $index) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center font-bold">
                                {{ substr($ranking->student->name ?? 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $ranking->student->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $ranking->student->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm text-gray-700">{{ $ranking->classRoom->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm text-gray-700">{{ $ranking->subject->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-2xl font-bold
                            @if($ranking->gpa >= 3.5) text-green-600
                            @elseif($ranking->gpa >= 3.0) text-blue-600
                            @elseif($ranking->gpa >= 2.5) text-yellow-600
                            @else text-red-600
                            @endif">
                            {{ number_format($ranking->gpa, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-semibold text-blue-600">{{ number_format($ranking->average_score, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-medium text-gray-700">{{ $ranking->total_exams_taken }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-medium text-green-600">{{ $ranking->total_exams_passed }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all
                                    @if($ranking->attendance_rate >= 80) bg-green-500
                                    @elseif($ranking->attendance_rate >= 60) bg-blue-500
                                    @else bg-yellow-500
                                    @endif"
                                    style="width: {{ $ranking->attendance_rate ?? 0 }}%">
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-600">{{ number_format($ranking->attendance_rate ?? 0, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="text-6xl mb-4">🏆</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có dữ liệu xếp hạng</h3>
                        <p class="text-gray-600">Hệ thống sẽ tự động tính toán xếp hạng khi có dữ liệu</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rankings->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $rankings->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});

function recalculateRankings() {
    const btn = document.getElementById('recalculateBtn');
    const originalText = btn.innerHTML;
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<i data-feather="loader" class="w-4 h-4 inline mr-1 animate-spin"></i> Đang tính toán...';
    feather.replace();
    
    // Get class_room_id if filtered
    const classRoomId = document.getElementById('filterClassRoom').value;
    const url = '{{ route("admin.statistics.rankings.recalculate") }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            class_room_id: classRoomId || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('success', data.message);
            
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Có lỗi xảy ra');
            btn.disabled = false;
            btn.innerHTML = originalText;
            feather.replace();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Không thể kết nối đến server');
        btn.disabled = false;
        btn.innerHTML = originalText;
        feather.replace();
    });
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i data-feather="${type === 'success' ? 'check-circle' : 'x-circle'}" class="w-5 h-5"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    feather.replace();
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
