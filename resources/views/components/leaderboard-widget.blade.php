<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Top Performers 🏆</h2>
    
    <div class="space-y-4">
        @foreach([
            ['name' => 'Sarah Johnson', 'score' => 2580, 'avatar' => 'SJ', 'rank' => 1],
            ['name' => 'Michael Chen', 'score' => 2450, 'avatar' => 'MC', 'rank' => 2],
            ['name' => 'Emma Davis', 'score' => 2380, 'avatar' => 'ED', 'rank' => 3],
            ['name' => 'John Doe', 'score' => 2320, 'avatar' => 'JD', 'rank' => 4],
            ['name' => 'Alex Kumar', 'score' => 2280, 'avatar' => 'AK', 'rank' => 5],
        ] as $user)
        <div class="flex items-center justify-between p-3 {{ $user['rank'] <= 3 ? 'bg-gradient-to-r from-yellow-50 to-orange-50' : 'bg-gray-50' }} rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 {{ $user['rank'] === 1 ? 'bg-yellow-400' : ($user['rank'] === 2 ? 'bg-gray-300' : ($user['rank'] === 3 ? 'bg-orange-400' : 'bg-purple-200')) }} rounded-full flex items-center justify-center font-bold text-sm">
                    {{ $user['rank'] }}
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user['name']) }}&background=random" class="w-10 h-10 rounded-full" alt="{{ $user['name'] }}">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $user['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $user['score'] }} points</p>
                </div>
            </div>
            @if($user['rank'] <= 3)
            <span class="text-2xl">{{ $user['rank'] === 1 ? '🥇' : ($user['rank'] === 2 ? '🥈' : '🥉') }}</span>
            @endif
        </div>
        @endforeach
    </div>
    
    <button class="w-full mt-4 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
        View Full Leaderboard
    </button>
</div>
