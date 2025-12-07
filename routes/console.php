<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Thống kê điểm số và xếp hạng
// Schedule automatic ranking calculation
Schedule::command('rankings:calculate')
    ->dailyAt('02:00') // Run at 2:00 AM every day
    ->withoutOverlapping()
    ->runInBackground()
    ->onSuccess(function () {
        \Log::info('Student rankings calculated successfully');
    })
    ->onFailure(function () {
        \Log::error('Failed to calculate student rankings');
    });

