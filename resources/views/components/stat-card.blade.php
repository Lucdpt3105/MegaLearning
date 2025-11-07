@props(['title', 'value', 'change', 'icon', 'color' => 'blue'])

@php
$colorClasses = [
    'blue' => 'from-blue-500 to-blue-600',
    'green' => 'from-green-500 to-green-600',
    'yellow' => 'from-yellow-500 to-orange-500',
    'purple' => 'from-purple-500 to-purple-600',
    'red' => 'from-red-500 to-red-600',
];
@endphp

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group cursor-pointer">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br {{ $colorClasses[$color] }} rounded-xl flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform">
                {{ $icon }}
            </div>
            <span class="text-green-500 text-sm font-semibold bg-green-50 px-3 py-1 rounded-full">
                {{ $change }}
            </span>
        </div>
        
        <h3 class="text-gray-500 text-sm font-medium mb-1">{{ $title }}</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
    </div>
    
    <div class="h-1 bg-gradient-to-r {{ $colorClasses[$color] }} transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
</div>
