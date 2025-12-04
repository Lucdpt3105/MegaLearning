@extends('layouts.app')

@section('title', 'Leaderboard - MegaLearning')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">🏆 Leaderboard</h1>
            <p class="text-lg text-gray-600">Top performers this month</p>
        </div>

        <!-- Top 3 Podium -->
        <div class="grid grid-cols-3 gap-4 mb-8 items-end">
            <!-- 2nd Place -->
            <div class="text-center">
                <div class="card-modern p-6 bg-gradient-to-br from-gray-100 to-gray-200">
                    <div class="relative inline-block mb-4">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Lee&size=80&background=E5E7EB&color=1F2937&bold=true" 
                             alt="Sarah Lee" 
                             class="w-20 h-20 rounded-full border-4 border-gray-400 shadow-lg">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            2
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg">Sarah Lee</h3>
                    <p class="text-sm text-gray-600 mb-3">Physics Master</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Score:</span>
                            <span class="font-bold text-gray-900">2,845</span>
                        </div>
                        <div class="progress-bar h-2">
                            <div class="h-full bg-gradient-to-r from-gray-400 to-gray-500 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2 justify-center">
                        <span class="badge-modern badge-blue text-xs">Top Scorer</span>
                    </div>
                </div>
            </div>

            <!-- 1st Place (Taller) -->
            <div class="text-center transform scale-110">
                <div class="card-modern p-6 bg-gradient-to-br from-yellow-100 via-yellow-200 to-yellow-300 border-4 border-yellow-400">
                    <div class="relative inline-block mb-4">
                        <img src="https://ui-avatars.com/api/?name=Alex+Johnson&size=96&background=FBBF24&color=78350F&bold=true" 
                             alt="Alex Johnson" 
                             class="w-24 h-24 rounded-full border-4 border-yellow-500 shadow-xl">
                        <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            👑
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 text-xl">Alex Johnson</h3>
                    <p class="text-sm text-gray-700 mb-3">Math Genius</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Score:</span>
                            <span class="font-bold text-gray-900 text-lg">3,120</span>
                        </div>
                        <div class="progress-bar h-2">
                            <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2 justify-center flex-wrap">
                        <span class="badge-modern text-xs" style="background: #FEF3C7; color: #78350F;">🥇 Champion</span>
                        <span class="badge-modern badge-green text-xs">Streak: 30d</span>
                    </div>
                </div>
            </div>

            <!-- 3rd Place -->
            <div class="text-center">
                <div class="card-modern p-6 bg-gradient-to-br from-orange-100 to-orange-200">
                    <div class="relative inline-block mb-4">
                        <img src="https://ui-avatars.com/api/?name=Mike+Chen&size=80&background=FDBA74&color=7C2D12&bold=true" 
                             alt="Mike Chen" 
                             class="w-20 h-20 rounded-full border-4 border-orange-400 shadow-lg">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            3
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg">Mike Chen</h3>
                    <p class="text-sm text-gray-600 mb-3">Chemistry Pro</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Score:</span>
                            <span class="font-bold text-gray-900">2,590</span>
                        </div>
                        <div class="progress-bar h-2">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-orange-500 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2 justify-center">
                        <span class="badge-modern badge-purple text-xs">Rising Star</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rankings List -->
        <div class="card-modern overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">All Rankings</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <!-- Rank 4 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 text-center">
                            <span class="text-2xl font-bold text-gray-400">4</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Emma+Wilson&size=48&background=3B82F6&color=FFFFFF&bold=true" 
                             alt="Emma Wilson" 
                             class="w-12 h-12 rounded-full border-2 border-blue-200">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Emma Wilson</h4>
                            <p class="text-sm text-gray-600">Biology Expert</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="badge-modern badge-green text-xs">Active Learner</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">2,340</p>
                            <div class="w-32 progress-bar h-1.5 mt-1">
                                <div class="progress-fill" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rank 5 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 text-center">
                            <span class="text-2xl font-bold text-gray-400">5</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=David+Kim&size=48&background=8B5CF6&color=FFFFFF&bold=true" 
                             alt="David Kim" 
                             class="w-12 h-12 rounded-full border-2 border-purple-200">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">David Kim</h4>
                            <p class="text-sm text-gray-600">History Buff</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="badge-modern badge-blue text-xs">Consistent</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">2,180</p>
                            <div class="w-32 progress-bar h-1.5 mt-1">
                                <div class="progress-fill" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rank 6 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 text-center">
                            <span class="text-2xl font-bold text-gray-400">6</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Lisa+Garcia&size=48&background=10B981&color=FFFFFF&bold=true" 
                             alt="Lisa Garcia" 
                             class="w-12 h-12 rounded-full border-2 border-green-200">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Lisa Garcia</h4>
                            <p class="text-sm text-gray-600">English Master</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="badge-modern badge-purple text-xs">Bookworm</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">2,050</p>
                            <div class="w-32 progress-bar h-1.5 mt-1">
                                <div class="progress-fill" style="width: 66%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rank 7 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 text-center">
                            <span class="text-2xl font-bold text-gray-400">7</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Tom+Brown&size=48&background=F59E0B&color=FFFFFF&bold=true" 
                             alt="Tom Brown" 
                             class="w-12 h-12 rounded-full border-2 border-orange-200">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Tom Brown</h4>
                            <p class="text-sm text-gray-600">Computer Science</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="badge-modern badge-orange text-xs">Fast Learner</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">1,920</p>
                            <div class="w-32 progress-bar h-1.5 mt-1">
                                <div class="progress-fill" style="width: 62%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Rank (Highlighted) -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 text-center">
                            <span class="text-2xl font-bold text-blue-600">15</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=You&size=48&background=3B82F6&color=FFFFFF&bold=true" 
                             alt="You" 
                             class="w-12 h-12 rounded-full border-4 border-blue-500 shadow-lg">
                        <div class="flex-1">
                            <h4 class="font-bold text-blue-600">You ({{ auth()->user()->name ?? 'Student' }})</h4>
                            <p class="text-sm text-gray-600">Keep pushing! 💪</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="badge-modern badge-blue text-xs">Your Rank</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">1,520</p>
                            <div class="w-32 progress-bar h-1.5 mt-1">
                                <div class="progress-fill bg-gradient-to-r from-blue-500 to-purple-500" style="width: 49%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement Badges Section -->
        <div class="card-modern p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Achievement Badges</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                    <div class="text-4xl mb-2">🥇</div>
                    <p class="text-xs font-semibold text-gray-700">Top 1</p>
                    <p class="text-xs text-gray-500">Champion</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                    <div class="text-4xl mb-2">📚</div>
                    <p class="text-xs font-semibold text-gray-700">Bookworm</p>
                    <p class="text-xs text-gray-500">Read 100+</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                    <div class="text-4xl mb-2">⚡</div>
                    <p class="text-xs font-semibold text-gray-700">Fast Learner</p>
                    <p class="text-xs text-gray-500">Quick Study</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                    <div class="text-4xl mb-2">🔥</div>
                    <p class="text-xs font-semibold text-gray-700">Streak Master</p>
                    <p class="text-xs text-gray-500">30 Days+</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl">
                    <div class="text-4xl mb-2">🎯</div>
                    <p class="text-xs font-semibold text-gray-700">Perfect Score</p>
                    <p class="text-xs text-gray-500">100% Exams</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl">
                    <div class="text-4xl mb-2">🌟</div>
                    <p class="text-xs font-semibold text-gray-700">Rising Star</p>
                    <p class="text-xs text-gray-500">Top 10</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
