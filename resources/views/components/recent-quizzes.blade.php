<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Recent Quizzes</h2>
        <a href="/quizzes" class="text-purple-600 hover:text-purple-700 text-sm font-semibold">View All →</a>
    </div>

    <div class="space-y-4">
        @foreach([
            ['title' => 'JavaScript Fundamentals', 'questions' => 20, 'time' => '30 min', 'difficulty' => 'Easy', 'score' => 85],
            ['title' => 'React Advanced Concepts', 'questions' => 15, 'time' => '25 min', 'difficulty' => 'Hard', 'score' => 72],
            ['title' => 'PHP & Laravel Basics', 'questions' => 25, 'time' => '40 min', 'difficulty' => 'Medium', 'score' => 90],
        ] as $quiz)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer group">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                    {{ $quiz['questions'] }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-purple-600 transition">{{ $quiz['title'] }}</h3>
                    <div class="flex items-center space-x-3 text-sm text-gray-500 mt-1">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $quiz['time'] }}
                        </span>
                        <span class="px-2 py-1 bg-{{ $quiz['difficulty'] === 'Easy' ? 'green' : ($quiz['difficulty'] === 'Hard' ? 'red' : 'yellow') }}-100 text-{{ $quiz['difficulty'] === 'Easy' ? 'green' : ($quiz['difficulty'] === 'Hard' ? 'red' : 'yellow') }}-700 rounded text-xs font-semibold">
                            {{ $quiz['difficulty'] }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-right">
                <div class="text-2xl font-bold text-purple-600">{{ $quiz['score'] }}%</div>
                <div class="text-xs text-gray-500">Your Score</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
