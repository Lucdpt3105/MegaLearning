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

// Test route - no auth needed
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

// Quick token generator for testing (REMOVE IN PRODUCTION!)
Route::post('/dev-token', function (Request $request) {
    try {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Only available in local environment'], 403);
        }
        
        $email = $request->input('email', 'admin@megalearning.com');
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'email_tried' => $email
            ], 404);
        }
        
        // Delete old tokens
        $user->tokens()->delete();
        
        // Create new token
        $token = $user->createToken('dev-token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'message' => 'Development token generated',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()
            ],
            'usage' => 'Add to headers -> Authorization: Bearer ' . substr($token, 0, 20) . '...'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error generating token',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Authentication Routes (Public)
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);
});

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Categories API - Public GET, Protected Create/Update/Delete
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
    Route::get('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show']);
    
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::put('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::patch('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy']);
    });
    
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
    // TEMPORARILY COMMENTED OUT - Fix ForumQuestionController issue
    /*
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
    */
    
    // Chat API Routes - MOVED TO web.php for session support
    // See routes/web.php for chat endpoints
    /*
    Route::prefix('chat')->group(function () {
        Route::get('current-user', [ChatController::class, 'getCurrentUser']);
        Route::middleware('auth')->group(function () {
            Route::get('rooms', [ChatController::class, 'getRooms']);
            Route::get('rooms/{roomId}/messages', [ChatController::class, 'getMessages']);
            Route::get('users', [ChatController::class, 'getUsers']);
            Route::post('rooms', [ChatController::class, 'store']);
            Route::post('rooms/{roomId}/messages', [ChatController::class, 'sendMessage']);
            Route::post('upload', [ChatController::class, 'uploadFile']);
            Route::post('rooms/{roomId}/mark-read', [ChatController::class, 'markAsRead']);
            Route::get('unread-count', [ChatController::class, 'getTotalUnreadCount']);
            Route::post('rooms/{roomId}/members', [ChatController::class, 'addMember']);
            Route::delete('rooms/{roomId}/members/{userId}', [ChatController::class, 'removeMember']);
            Route::put('rooms/{roomId}', [ChatController::class, 'update']);
            Route::delete('rooms/{roomId}', [ChatController::class, 'destroy']);
            Route::post('rooms/private', [ChatController::class, 'createPrivateRoom']);
        });
        Route::post('set-user', [ChatController::class, 'setUser']);
    });
    */
});
