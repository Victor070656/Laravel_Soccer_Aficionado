<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClubApiController;
use App\Http\Controllers\Api\CommunityApiController;
use App\Http\Controllers\Api\CompetitionApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\LeaderboardApiController;
use App\Http\Controllers\Api\MatchApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\PollApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\SearchApiController;
use Illuminate\Support\Facades\Route;

// ── Public API Routes ──────────────────────────────────
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public data — Matches
    Route::get('/matches', [MatchApiController::class, 'index']);
    Route::get('/matches/live', [MatchApiController::class, 'live']);
    Route::get('/matches/{id}', [MatchApiController::class, 'show'])->where('id', '[0-9]+');

    // Public data — Clubs
    Route::get('/clubs', [ClubApiController::class, 'index']);
    Route::get('/clubs/{id}', [ClubApiController::class, 'show'])->where('id', '[0-9]+');

    // Public data — Competitions
    Route::get('/competitions', [CompetitionApiController::class, 'index']);
    Route::get('/competitions/{id}', [CompetitionApiController::class, 'show'])->where('id', '[0-9]+');

    // Public data — Posts
    Route::get('/posts', [PostApiController::class, 'index']);
    Route::get('/posts/{post}', [PostApiController::class, 'show']);

    // Public data — Communities
    Route::get('/communities', [CommunityApiController::class, 'index']);
    Route::get('/communities/{community}', [CommunityApiController::class, 'show']);

    // Public data — Polls
    Route::get('/polls', [PollApiController::class, 'index']);
    Route::get('/polls/{poll}', [PollApiController::class, 'show']);

    // Public data — User Profiles
    Route::get('/users/{user:username}', [ProfileApiController::class, 'show']);

    // Public data — Search
    Route::get('/search', SearchApiController::class);

    // Public data — Leaderboard
    Route::get('/leaderboard', LeaderboardApiController::class);

    // ── Authenticated API Routes ───────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/user', [AuthController::class, 'updateProfile']);

        // Dashboard
        Route::get('/dashboard', DashboardApiController::class);

        // Posts
        Route::get('/feed', [PostApiController::class, 'feed']);
        Route::post('/posts', [PostApiController::class, 'store']);
        Route::put('/posts/{post}', [PostApiController::class, 'update']);
        Route::delete('/posts/{post}', [PostApiController::class, 'destroy']);
        Route::post('/posts/{post}/like', [PostApiController::class, 'like']);
        Route::post('/posts/{post}/comment', [PostApiController::class, 'comment']);
        Route::post('/posts/{post}/share', [PostApiController::class, 'share']);
        Route::post('/posts/{post}/pin', [PostApiController::class, 'pin']);
        Route::delete('/comments/{comment}', [PostApiController::class, 'deleteComment']);

        // Communities
        Route::post('/communities', [CommunityApiController::class, 'store']);
        Route::post('/communities/{community}/join', [CommunityApiController::class, 'join']);
        Route::post('/communities/{community}/leave', [CommunityApiController::class, 'leave']);

        // Polls
        Route::post('/polls', [PollApiController::class, 'store']);
        Route::post('/polls/{poll}/vote', [PollApiController::class, 'vote']);

        // Social — Follow / Unfollow
        Route::post('/users/{user}/follow', [ProfileApiController::class, 'follow']);

        // Reports
        Route::post('/reports', [ReportApiController::class, 'store']);

        // Notifications
        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount']);
        Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
    });
});
