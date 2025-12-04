<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ForumQuestionController;
use App\Http\Controllers\ChatController;

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

    // Forum Q&A (public index/show, rest require auth)
    Route::prefix('forum')->group(function () {
        Route::get('questions', [ForumQuestionController::class, 'index']);
        Route::get('questions/{id}', [ForumQuestionController::class, 'show']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('questions', [ForumQuestionController::class, 'store']);
            Route::put('questions/{id}', [ForumQuestionController::class, 'update']);
            Route::delete('questions/{id}', [ForumQuestionController::class, 'destroy']);
            Route::post('questions/{id}/answers', [ForumQuestionController::class, 'storeAnswer']);
            Route::post('questions/{id}/vote/up', [ForumQuestionController::class, 'voteUp']);
            Route::post('questions/{id}/vote/down', [ForumQuestionController::class, 'voteDown']);
            Route::post('questions/{questionId}/answers/{answerId}/vote/up', [ForumQuestionController::class, 'voteAnswerUp']);
            Route::post('questions/{questionId}/answers/{answerId}/vote/down', [ForumQuestionController::class, 'voteAnswerDown']);
            Route::delete('questions/{questionId}/answers/{answerId}', [ForumQuestionController::class, 'destroyAnswer']);
        });
    });
    
    // Chat API Routes - use web middleware for session support
    Route::middleware(['web'])->prefix('chat')->group(function () {
        // Public routes for chat (demo/testing) - with session/auth support
        Route::get('current-user', [ChatController::class, 'getCurrentUser']);
        Route::post('set-user', [ChatController::class, 'setUser']);
        Route::get('rooms', [ChatController::class, 'getRooms']);
        Route::get('rooms/{roomId}/messages', [ChatController::class, 'getMessages']);
        Route::post('rooms', [ChatController::class, 'store']);
        Route::post('rooms/{roomId}/messages', [ChatController::class, 'sendMessage']);
        Route::post('rooms/{roomId}/mark-read', [ChatController::class, 'markAsRead']);
        Route::get('users', [ChatController::class, 'getUsers']);
        Route::get('unread-count', [ChatController::class, 'getTotalUnreadCount']);
        
        // Authenticated routes
        Route::middleware(['auth'])->group(function () {
            Route::post('rooms/{roomId}/members', [ChatController::class, 'addMember']);
            Route::delete('rooms/{roomId}/members/{userId}', [ChatController::class, 'removeMember']);
            Route::put('rooms/{roomId}', [ChatController::class, 'update']);
            Route::delete('rooms/{roomId}', [ChatController::class, 'destroy']);
        });
    });
});
