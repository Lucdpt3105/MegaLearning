@extends('layouts.app')

@section('title', 'Exam - MegaLearning')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- Exam Header with Timer -->
        <div class="card-modern p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mathematics Final Exam</h1>
                    <p class="text-gray-600 mt-1">Chapter 1-5 • 30 Questions • 60 Minutes</p>
                </div>
                
                <!-- Countdown Timer -->
                <div class="text-center">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-br from-orange-500 to-red-600 text-white px-6 py-3 rounded-xl shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-3xl font-bold font-mono" id="timer">45:30</div>
                            <div class="text-xs text-orange-100">Time Remaining</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Progress</span>
                    <span class="text-sm font-semibold text-blue-600">12/30 Questions</span>
                </div>
                <div class="progress-bar h-3">
                    <div class="progress-fill" style="width: 40%"></div>
                </div>
            </div>
        </div>

        <!-- Question Navigation Grid -->
        <div class="card-modern p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Question Navigator</h3>
            <div class="grid grid-cols-10 gap-2">
                @for($i = 1; $i <= 30; $i++)
                    <button class="
                        w-10 h-10 rounded-lg font-semibold text-sm transition-all duration-200
                        @if($i <= 12)
                            bg-gradient-to-br from-green-500 to-green-600 text-white hover:shadow-lg
                        @elseif($i == 13)
                            bg-gradient-to-br from-blue-500 to-blue-600 text-white ring-4 ring-blue-200 scale-110
                        @else
                            bg-gray-100 text-gray-600 hover:bg-gray-200
                        @endif
                    ">
                        {{ $i }}
                    </button>
                @endfor
            </div>
            <div class="flex gap-4 mt-4 text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-green-500 to-green-600"></div>
                    <span class="text-gray-600">Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-blue-500 to-blue-600"></div>
                    <span class="text-gray-600">Current</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gray-100"></div>
                    <span class="text-gray-600">Not Answered</span>
                </div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="card-modern p-8 mb-6">
            <!-- Question Number Badge -->
            <div class="flex items-center justify-between mb-6">
                <div class="badge-modern badge-blue text-base px-4 py-2">
                    Question 13 of 30
                </div>
                <button class="text-gray-400 hover:text-yellow-500 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            </div>

            <!-- Question Text -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 leading-relaxed">
                    What is the derivative of the function f(x) = 3x² + 5x - 2?
                </h2>
                
                <!-- Optional: Question Image -->
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hidden">
                    <img src="/images/math-diagram.png" alt="Diagram" class="mx-auto max-h-64">
                </div>
            </div>

            <!-- Answer Options -->
            <div class="space-y-3">
                <!-- Option A -->
                <label class="block cursor-pointer group">
                    <div class="card-modern p-5 hover:shadow-lg transition-all duration-200 group-hover:scale-[1.02] border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-start gap-4">
                            <input type="radio" name="answer" value="A" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-blue-600">A.</span>
                                    <span class="text-gray-800 font-medium">f'(x) = 6x + 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Option B -->
                <label class="block cursor-pointer group">
                    <div class="card-modern p-5 hover:shadow-lg transition-all duration-200 group-hover:scale-[1.02] border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-start gap-4">
                            <input type="radio" name="answer" value="B" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-blue-600">B.</span>
                                    <span class="text-gray-800 font-medium">f'(x) = 3x + 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Option C -->
                <label class="block cursor-pointer group">
                    <div class="card-modern p-5 hover:shadow-lg transition-all duration-200 group-hover:scale-[1.02] border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-start gap-4">
                            <input type="radio" name="answer" value="C" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-blue-600">C.</span>
                                    <span class="text-gray-800 font-medium">f'(x) = 6x + 2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Option D -->
                <label class="block cursor-pointer group">
                    <div class="card-modern p-5 hover:shadow-lg transition-all duration-200 group-hover:scale-[1.02] border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-start gap-4">
                            <input type="radio" name="answer" value="D" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-blue-600">D.</span>
                                    <span class="text-gray-800 font-medium">f'(x) = 9x + 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between mb-8">
            <button class="btn-modern btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>

            <div class="flex gap-3">
                <button class="btn-modern bg-yellow-500 text-white hover:bg-yellow-600">
                    Mark for Review
                </button>
                <button class="btn-modern bg-gray-600 text-white hover:bg-gray-700">
                    Skip
                </button>
            </div>

            <button class="btn-modern btn-primary flex items-center gap-2">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Submit Exam Button -->
        <div class="card-modern p-6 text-center bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-blue-200">
            <p class="text-gray-700 mb-4">Ready to submit your exam?</p>
            <button class="btn-modern btn-success px-8 py-4 text-lg">
                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Submit Exam
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple countdown timer
    let totalSeconds = 45 * 60 + 30; // 45:30
    
    function updateTimer() {
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        document.getElementById('timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (totalSeconds > 0) {
            totalSeconds--;
        } else {
            alert('Time is up! Submitting exam...');
        }
    }
    
    setInterval(updateTimer, 1000);
</script>
@endpush
