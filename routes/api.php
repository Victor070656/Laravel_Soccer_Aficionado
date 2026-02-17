<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityApiController;
use App\Http\Controllers\Api\MatchApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\PollApiController;
use App\Http\Controllers\Api\PostApiController;
use Illuminate\Support\Facades\Route;

// ── Public API Routes ──────────────────────────────────
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public data
    Route::get('/matches', [MatchApiController::class, 'index']);
    Route::get('/matches/live', [MatchApiController::class, 'live']);
    Route::get('/matches/{id}', [MatchApiController::class, 'show'])->where('id', '[0-9]+');

    Route::get('/posts', [PostApiController::class, 'index']);
    Route::get('/posts/{post}', [PostApiController::class, 'show']);

    Route::get('/communities', [CommunityApiController::class, 'index']);
    Route::get('/communities/{community}', [CommunityApiController::class, 'show']);

    Route::get('/polls', [PollApiController::class, 'index']);
    Route::get('/polls/{poll}', [PollApiController::class, 'show']);

    // ── Authenticated API Routes ───────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/user', [AuthController::class, 'updateProfile']);

        // Posts
        Route::get('/feed', [PostApiController::class, 'feed']);
        Route::post('/posts', [PostApiController::class, 'store']);
        Route::delete('/posts/{post}', [PostApiController::class, 'destroy']);
        Route::post('/posts/{post}/like', [PostApiController::class, 'like']);
        Route::post('/posts/{post}/comment', [PostApiController::class, 'comment']);

        // Communities
        Route::post('/communities/{community}/join', [CommunityApiController::class, 'join']);
        Route::post('/communities/{community}/leave', [CommunityApiController::class, 'leave']);

        // Polls
        Route::post('/polls/{poll}/vote', [PollApiController::class, 'vote']);

        // Notifications
        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount']);
        Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
    });
});
