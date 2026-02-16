<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClubManagementController;
use App\Http\Controllers\Admin\MatchManagementController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public browsing
Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/live', [MatchController::class, 'live'])->name('matches.live');
Route::get('/matches/{id}', [MatchController::class, 'show'])->name('matches.show')->where('id', '[0-9]+');

Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
Route::get('/clubs/{club:slug}', [ClubController::class, 'show'])->name('clubs.show');

Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions/{competition:slug}', [CompetitionController::class, 'show'])->name('competitions.show');

Route::get('/users/{user:username}', [ProfileController::class, 'show'])->name('profiles.show');

Route::get('/search', SearchController::class)->name('search');

// ── Authenticated Routes ───────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');

    // Communities
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/{community:slug}', [CommunityController::class, 'show'])->name('communities.show');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

    // Polls
    Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}', [PollController::class, 'show'])->name('polls.show');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');

    // Social
    Route::post('/users/{user}/follow', [ProfileController::class, 'follow'])->name('users.follow');
    Route::post('/clubs/{club}/favorite', [ClubController::class, 'toggleFavorite'])->name('clubs.favorite');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Leaderboard
    Route::get('/leaderboard', LeaderboardController::class)->name('leaderboard');
});

// ── Admin Routes ───────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', AdminDashboardController::class)->name('dashboard');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/ban', [UserManagementController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserManagementController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('users.assignRole');
    Route::post('/users/{user}/remove-role', [UserManagementController::class, 'removeRole'])->name('users.removeRole');

    // Club Management
    Route::resource('clubs', ClubManagementController::class)->except('show');

    // Match Management
    Route::resource('matches', MatchManagementController::class)->except('show');

    // Moderation
    Route::get('/moderation/reports', [ModerationController::class, 'reports'])->name('moderation.reports');
    Route::post('/moderation/reports/{report}', [ModerationController::class, 'reviewReport'])->name('moderation.reviewReport');
    Route::get('/moderation/pending-posts', [ModerationController::class, 'pendingPosts'])->name('moderation.pendingPosts');
    Route::post('/moderation/posts/{post}/approve', [ModerationController::class, 'approvePost'])->name('moderation.approvePost');
    Route::delete('/moderation/posts/{post}/reject', [ModerationController::class, 'rejectPost'])->name('moderation.rejectPost');
});

require __DIR__ . '/settings.php';
