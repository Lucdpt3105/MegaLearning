@extends('admin.layout')

@section('title', 'Lịch họp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Lịch họp</h1>
        <a href="{{ route('admin.meetings.schedule.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i data-feather="plus" class="w-4 h-4 inline"></i> Lên lịch họp
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-7 gap-2">
            <div class="font-semibold text-center">CN</div>
            <div class="font-semibold text-center">T2</div>
            <div class="font-semibold text-center">T3</div>
            <div class="font-semibold text-center">T4</div>
            <div class="font-semibold text-center">T5</div>
            <div class="font-semibold text-center">T6</div>
            <div class="font-semibold text-center">T7</div>
        </div>
        <div class="mt-4 text-center text-gray-500">
            Lịch họp sẽ hiển thị tại đây
        </div>
    </div>
</div>
@endsection
