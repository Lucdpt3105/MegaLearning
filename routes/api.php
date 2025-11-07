<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ExamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Version 1
Route::prefix('v1')->group(function () {
    // Subjects API
    Route::apiResource('subjects', SubjectController::class);
    
    // Topics API
    Route::apiResource('topics', TopicController::class);
    
    // Questions API
    Route::apiResource('questions', QuestionController::class);
    
    // Exams API
    Route::apiResource('exams', ExamController::class);
});
