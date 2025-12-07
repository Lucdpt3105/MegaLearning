@extends('layouts.app')

@section('title', 'Question Bank - MegaLearning')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Question Bank</h1>
                <p class="text-lg text-gray-600 mt-2">Manage your questions and question pools</p>
            </div>
            
            <div class="flex gap-3">
                <button class="btn-modern btn-secondary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
                
                <button class="btn-modern btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Question
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card-modern p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Questions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">143</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="card-modern p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Multiple Choice</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">89</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="card-modern p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">True/False</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">34</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="card-modern p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Essay</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">20</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="card-modern p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search questions by title or content..."
                            class="input-modern pl-12"
                        />
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Subject Filter -->
                <div>
                    <select class="input-modern">
                        <option>All Subjects</option>
                        <option>Mathematics</option>
                        <option>Physics</option>
                        <option>Chemistry</option>
                        <option>English</option>
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <select class="input-modern">
                        <option>All Levels</option>
                        <option>Easy</option>
                        <option>Medium</option>
                        <option>Hard</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            <div class="flex gap-2 mt-4">
                <span class="text-sm text-gray-600 font-medium">Active Filters:</span>
                <div class="flex gap-2 flex-wrap">
                    <span class="badge-modern badge-blue flex items-center gap-1">
                        Mathematics
                        <button class="hover:text-blue-900">×</button>
                    </span>
                    <span class="badge-modern badge-green flex items-center gap-1">
                        Easy Level
                        <button class="hover:text-green-900">×</button>
                    </span>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="card-modern overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-700">
                    <div class="col-span-1">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    </div>
                    <div class="col-span-5">Question</div>
                    <div class="col-span-2">Subject</div>
                    <div class="col-span-1">Type</div>
                    <div class="col-span-1">Difficulty</div>
                    <div class="col-span-2">Actions</div>
                </div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-100">
                <!-- Question Row 1 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-1">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                        <div class="col-span-5">
                            <h4 class="font-medium text-gray-900 mb-1">What is the derivative of f(x) = 3x² + 5x - 2?</h4>
                            <div class="flex gap-2">
                                <span class="badge-modern badge-purple text-xs">Calculus</span>
                                <span class="badge-modern badge-blue text-xs">Chapter 5</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm text-gray-700">Mathematics</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern badge-green text-xs">MCQ</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern badge-orange text-xs">Medium</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <button class="p-2 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-purple-100 rounded-lg transition-colors" title="Preview">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Question Row 2 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-1">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                        <div class="col-span-5">
                            <h4 class="font-medium text-gray-900 mb-1">Newton's second law of motion states that...</h4>
                            <div class="flex gap-2">
                                <span class="badge-modern badge-purple text-xs">Mechanics</span>
                                <span class="badge-modern badge-blue text-xs">Laws of Motion</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm text-gray-700">Physics</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern badge-blue text-xs">T/F</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern badge-green text-xs">Easy</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <button class="p-2 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-purple-100 rounded-lg transition-colors" title="Preview">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Question Row 3 -->
                <div class="px-6 py-4 hover:bg-blue-50 transition-colors">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-1">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                        <div class="col-span-5">
                            <h4 class="font-medium text-gray-900 mb-1">Explain the process of photosynthesis in plants</h4>
                            <div class="flex gap-2">
                                <span class="badge-modern badge-purple text-xs">Biology</span>
                                <span class="badge-modern badge-blue text-xs">Plant Systems</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm text-gray-700">Biology</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern badge-purple text-xs">Essay</span>
                        </div>
                        <div class="col-span-1">
                            <span class="badge-modern text-xs" style="background: #fee2e2; color: #991b1b;">Hard</span>
                        </div>
                        <div class="col-span-2 flex gap-2">
                            <button class="p-2 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-purple-100 rounded-lg transition-colors" title="Preview">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="p-2 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-semibold">1-10</span> of <span class="font-semibold">143</span> questions
                    </p>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>
                            Previous
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            1
                        </button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            2
                        </button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            3
                        </button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
