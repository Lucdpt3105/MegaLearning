@extends('layouts.app')

@section('title', 'Kết quả Lớp học')

@push('styles')
<style>
    .gradebook-table {
        overflow-x: auto;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
    }
    .gradebook-table table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .gradebook-table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 2px solid #e5e7eb;
    }
    .gradebook-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .gradebook-table tbody tr:hover {
        background: #f9fafb;
    }
    .score-cell {
        text-align: center;
        font-weight: 600;
    }
    .score-excellent { color: #059669; }
    .score-good { color: #3b82f6; }
    .score-average { color: #f59e0b; }
    .score-poor { color: #ef4444; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('teacher.reports.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">
                        ← Quay lại Báo cáo
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $classRoom->name }}</h1>
                    <p class="text-gray-600">{{ $classRoom->subject ? $classRoom->subject->name : 'N/A' }} • Kết quả tổng hợp</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.reports.print-gradebook', $classRoom->id) }}" 
                       target="_blank"
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        🖨️ In sổ điểm
                    </a>
                    <a href="{{ route('teacher.reports.export-gradebook', $classRoom->id) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        📥 Xuất Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Điểm cao nhất</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($classStats['highest_score'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Điểm thấp nhất</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($classStats['lowest_score'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Điểm trung bình</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($classStats['average_score'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Tỷ lệ đạt</p>
                <p class="text-2xl font-bold text-purple-600">{{ $classStats['pass_rate'] }}%</p>
            </div>
        </div>

        <!-- Gradebook Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📊 Sổ điểm Gradebook</h2>
            
            <div class="gradebook-table">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width: 50px;">#</th>
                            <th style="min-width: 200px;">Học sinh</th>
                            @foreach($exams as $exam)
                                <th style="min-width: 100px;" class="text-center">
                                    <div>{{ $exam->title }}</div>
                                    <div class="text-xs font-normal text-gray-500 mt-1">
                                        {{ $exam->created_at->format('d/m/Y') }}
                                    </div>
                                </th>
                            @endforeach
                            <th style="min-width: 100px;" class="text-center bg-blue-50">Điểm TB</th>
                            <th style="min-width: 100px;" class="text-center">Số bài đã làm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $studentData)
                            <tr>
                                <td class="text-gray-600">{{ $index + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($studentData['student']->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $studentData['student']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $studentData['student']->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                @foreach($studentData['scores'] as $score)
                                    <td class="score-cell {{ 
                                        $score === null ? '' : 
                                        ($score >= 8 ? 'score-excellent' : 
                                        ($score >= 6.5 ? 'score-good' : 
                                        ($score >= 5 ? 'score-average' : 'score-poor'))) 
                                    }}">
                                        @if($score !== null)
                                            {{ number_format($score, 2) }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="score-cell bg-blue-50 font-bold {{ 
                                    $studentData['average'] >= 8 ? 'score-excellent' : 
                                    ($studentData['average'] >= 6.5 ? 'score-good' : 
                                    ($studentData['average'] >= 5 ? 'score-average' : 'score-poor')) 
                                }}">
                                    {{ number_format($studentData['average'], 2) }}
                                </td>
                                <td class="text-center text-gray-600">
                                    {{ $studentData['completed_exams'] }}/{{ $exams->count() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $exams->count() + 4 }}" class="text-center text-gray-500 py-8">
                                    Chưa có học sinh nào trong lớp
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-4 flex items-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-green-100 border border-green-300"></span>
                    <span class="text-gray-600">Xuất sắc (≥8)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-blue-100 border border-blue-300"></span>
                    <span class="text-gray-600">Khá (6.5-7.9)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-orange-100 border border-orange-300"></span>
                    <span class="text-gray-600">Trung bình (5-6.4)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-red-100 border border-red-300"></span>
                    <span class="text-gray-600">Yếu (<5)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
