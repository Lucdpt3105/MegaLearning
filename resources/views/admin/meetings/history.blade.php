@extends('admin.layout')

@section('title', 'Lịch sử Họp')
@section('page-title', 'Lịch sử Họp')
@section('page-description', 'Xem lịch sử các cuộc họp đã diễn ra')

@section('content')

<!-- Monthly Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium mb-1">Tổng cuộc họp tháng này</p>
                <p class="text-4xl font-bold">{{ number_format($monthly_stats['total_meetings']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">📊</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-cyan-100 text-sm font-medium mb-1">Tổng thời gian họp</p>
                <p class="text-4xl font-bold">{{ number_format($monthly_stats['total_duration']) }}</p>
                <p class="text-xs text-cyan-100 mt-1">phút</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">⏰</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm font-medium mb-1">Trung bình/cuộc họp</p>
                <p class="text-4xl font-bold">{{ $monthly_stats['average_duration'] }}</p>
                <p class="text-xs text-teal-100 mt-1">phút</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">📈</span>
            </div>
        </div>
    </div>
</div>

<!-- Top Hosts -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i data-feather="award" class="w-5 h-5 inline text-yellow-500"></i>
        Top Chủ phòng họp
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($top_hosts as $index => $host)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $host->name }}</h4>
                        <p class="text-sm text-gray-600">
                            {{ $host->meetings_count }} cuộc họp | {{ $host->total_duration }} phút
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <form action="{{ route('admin.meetings.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
            <select name="class_room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                @foreach($classRooms as $class)
                    <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} - {{ $class->subject->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.meetings.history') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>

<!-- History Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <i data-feather="clock" class="w-5 h-5 inline text-blue-600"></i>
            Lịch sử Cuộc họp
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tiêu đề
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Lớp học
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Chủ phòng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thời gian
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thời lượng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Trạng thái
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($meetings as $meeting)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $meeting->title }}</div>
                            @if($meeting->description)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($meeting->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $meeting->classRoom ? $meeting->classRoom->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $meeting->host ? $meeting->host->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $meeting->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $meeting->duration }} phút
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $meeting->status == 'ended' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700' }}">
                                {{ $meeting->status == 'ended' ? 'Đã kết thúc' : 'Đã hủy' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
                            <p class="text-lg">Chưa có lịch sử cuộc họp</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($meetings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $meetings->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
