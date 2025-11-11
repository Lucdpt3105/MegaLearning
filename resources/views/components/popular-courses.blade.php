<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Popular Courses</h2>
        <a href="/courses" class="text-purple-600 hover:text-purple-700 text-sm font-semibold">View All →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['title' => 'Full Stack Web Development', 'students' => '2.5k', 'rating' => 4.8, 'price' => '$49', 'image' => '💻'],
            ['title' => 'Data Science & Machine Learning', 'students' => '1.8k', 'rating' => 4.9, 'price' => '$79', 'image' => '🤖'],
            ['title' => 'Mobile App Development', 'students' => '3.2k', 'rating' => 4.7, 'price' => '$59', 'image' => '📱'],
        ] as $course)
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl p-6 hover:shadow-lg transition-all duration-300 cursor-pointer group">
            <div class="text-6xl mb-4">{{ $course['image'] }}</div>
            <h3 class="font-bold text-gray-800 mb-2 group-hover:text-purple-600 transition">{{ $course['title'] }}</h3>
            
            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ $course['rating'] }}
                </span>
                <span>👥 {{ $course['students'] }}</span>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-purple-600">{{ $course['price'] }}</span>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition">
                    Enroll
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
