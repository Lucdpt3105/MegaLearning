<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Upcoming Events</h2>
        <button class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
    </div>

    <div class="space-y-4">
        @foreach([
            ['title' => 'Final Exam: Web Development', 'date' => 'Tomorrow', 'time' => '10:00 AM', 'color' => 'red'],
            ['title' => 'Live Session: React Hooks', 'date' => 'Nov 10', 'time' => '2:00 PM', 'color' => 'blue'],
            ['title' => 'Assignment Due: PHP Project', 'date' => 'Nov 12', 'time' => '11:59 PM', 'color' => 'orange'],
        ] as $event)
        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="w-12 h-12 bg-{{ $event['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-{{ $event['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800 mb-1">{{ $event['title'] }}</h3>
                <div class="flex items-center space-x-3 text-sm text-gray-500">
                    <span>📅 {{ $event['date'] }}</span>
                    <span>🕐 {{ $event['time'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button class="w-full mt-4 py-3 bg-purple-50 text-purple-600 rounded-lg font-semibold hover:bg-purple-100 transition">
        View Calendar
    </button>
</div>
