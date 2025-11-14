<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ForumQuestionController;

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


// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/forum/questions', [ForumQuestionController::class, 'index']);
//     Route::get('/forum/questions/{id}', [ForumQuestionController::class, 'show']);
//     Route::post('/forum/questions', [ForumQuestionController::class, 'store']);
//     Route::put('/forum/questions/{id}', [ForumQuestionController::class, 'update']);
//     Route::delete('/forum/questions/{id}', [ForumQuestionController::class, 'destroy']);
// });
// Route::middleware(['auth:sanctum', 'role:admin'])->delete('/forum/questions/{id}', [ForumQuestionController::class, 'destroy']);



// Authentication Routes (Public)
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);
});

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public routes - No authentication needed
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::get('subjects/{id}', [SubjectController::class, 'show']);
    
    // Protected routes - Require authentication + permissions
    Route::middleware(['auth:sanctum', 'permission:manage-subjects'])->group(function () {
        Route::post('subjects', [SubjectController::class, 'store']);
        Route::put('subjects/{id}', [SubjectController::class, 'update']);
        Route::delete('subjects/{id}', [SubjectController::class, 'destroy']);
    });
    
    // Topics API
    Route::get('topics', [TopicController::class, 'index']);
    Route::get('topics/{id}', [TopicController::class, 'show']);
    Route::middleware(['auth:sanctum', 'permission:manage-topics'])->group(function () {
        Route::post('topics', [TopicController::class, 'store']);
        Route::put('topics/{id}', [TopicController::class, 'update']);
        Route::delete('topics/{id}', [TopicController::class, 'destroy']);
    });
    
    // Questions API
    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('questions/{id}', [QuestionController::class, 'show']);
    Route::middleware(['auth:sanctum', 'permission:manage-questions'])->group(function () {
        Route::post('questions', [QuestionController::class, 'store']);
        Route::put('questions/{id}', [QuestionController::class, 'update']);
        Route::delete('questions/{id}', [QuestionController::class, 'destroy']);
    });
    
    // Exams API
    Route::get('exams', [ExamController::class, 'index']);
    Route::get('exams/{id}', [ExamController::class, 'show']);
    Route::middleware(['auth:sanctum', 'permission:manage-exams'])->group(function () {
        Route::post('exams', [ExamController::class, 'store']);
        Route::put('exams/{id}', [ExamController::class, 'update']);
        Route::delete('exams/{id}', [ExamController::class, 'destroy']);
    });
    Route::apiResource('topics', TopicController::class);
    
    // Questions API
    Route::apiResource('questions', QuestionController::class);
    
    // Exams API
    Route::apiResource('exams', ExamController::class);
});
