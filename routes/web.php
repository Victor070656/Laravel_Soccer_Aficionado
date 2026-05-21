<?php

use App\Http\Controllers\Admin\AdManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ClubManagementController;
use App\Http\Controllers\Admin\CommunityManagementController;
use App\Http\Controllers\Admin\CompetitionManagementController;
use App\Http\Controllers\Admin\MatchManagementController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\PollManagementController;
use App\Http\Controllers\Admin\UserManagementController;
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
use App\Services\FootballApiService;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────
Route::get('/', function (FootballApiService $api) {
    $liveMatches = collect($api->getLiveFixtures())
        ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

    $upcomingMatches = collect($api->getUpcomingFixtures(8))
        ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

    return view('welcome', compact('liveMatches', 'upcomingMatches'));
})->name('home');

// Public browsing
Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/live', [MatchController::class, 'live'])->name('matches.live');
Route::get('/matches/{id}', [MatchController::class, 'show'])->name('matches.show')->where('id', '[0-9]+');
Route::get('/matches/{id}/room', [MatchController::class, 'room'])->name('matches.room')->where('id', '[0-9]+');

Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
Route::get('/clubs/{id}', [ClubController::class, 'show'])->name('clubs.show')->where('id', '[0-9]+');

Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions/{id}', [CompetitionController::class, 'show'])->name('competitions.show')->where('id', '[0-9]+');

Route::get('/users/{user:username}', [ProfileController::class, 'show'])->name('profiles.show');

Route::get('/search', SearchController::class)->name('search');

// ── Authenticated Routes ───────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/feed', [\App\Http\Controllers\FeedController::class, 'index'])->name('feed');
    Route::get('/trending', [\App\Http\Controllers\TrendingController::class, 'index'])->name('trending');

    // Posts
    Route::redirect('/posts', '/feed')->name('posts.redirect');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/pin', [PostController::class, 'pin'])->name('posts.pin');
    Route::delete('/comments/{comment}', [PostController::class, 'deleteComment'])->name('comments.destroy');

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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

    // Community Management
    Route::get('/communities', [CommunityManagementController::class, 'index'])->name('communities.index');
    Route::post('/communities/{community}/toggle-active', [CommunityManagementController::class, 'toggleActive'])->name('communities.toggleActive');
    Route::delete('/communities/{community}', [CommunityManagementController::class, 'destroy'])->name('communities.destroy');

    // Poll Management
    Route::get('/polls', [PollManagementController::class, 'index'])->name('polls.index');
    Route::get('/polls/create', [PollManagementController::class, 'create'])->name('polls.create');
    Route::post('/polls', [PollManagementController::class, 'store'])->name('polls.store');
    Route::post('/polls/{poll}/close', [PollManagementController::class, 'close'])->name('polls.close');
    Route::post('/polls/{poll}/reopen', [PollManagementController::class, 'reopen'])->name('polls.reopen');
    Route::delete('/polls/{poll}', [PollManagementController::class, 'destroy'])->name('polls.destroy');

    // Moderation
    Route::get('/moderation/reports', [ModerationController::class, 'reports'])->name('moderation.reports');
    Route::post('/moderation/reports/{report}', [ModerationController::class, 'reviewReport'])->name('moderation.reviewReport');
    Route::get('/moderation/pending-posts', [ModerationController::class, 'pendingPosts'])->name('moderation.pendingPosts');
    Route::post('/moderation/posts/{post}/approve', [ModerationController::class, 'approvePost'])->name('moderation.approvePost');
    Route::delete('/moderation/posts/{post}/reject', [ModerationController::class, 'rejectPost'])->name('moderation.rejectPost');
    Route::get('/moderation/pending-comments', [ModerationController::class, 'pendingComments'])->name('moderation.pendingComments');
    Route::post('/moderation/comments/{comment}/approve', [ModerationController::class, 'approveComment'])->name('moderation.approveComment');
    Route::delete('/moderation/comments/{comment}', [ModerationController::class, 'deleteComment'])->name('moderation.deleteComment');

    // Match Management (API-based)
    Route::get('/matches', [MatchManagementController::class, 'index'])->name('matches.index');
    Route::get('/matches/{id}', [MatchManagementController::class, 'show'])->name('matches.show')->where('id', '[0-9]+');
    Route::post('/matches/sync-api', [MatchManagementController::class, 'syncFromApi'])->name('matches.syncApi');
    Route::post('/matches/sync-teams', [MatchManagementController::class, 'syncTeams'])->name('matches.syncTeams');

    // Club Management (API-based)
    Route::get('/clubs', [ClubManagementController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/{id}', [ClubManagementController::class, 'show'])->name('clubs.show')->where('id', '[0-9]+');
    Route::post('/clubs/{id}/sync', [ClubManagementController::class, 'syncClub'])->name('clubs.syncClub')->where('id', '[0-9]+');
    Route::post('/clubs/sync-league', [ClubManagementController::class, 'syncLeague'])->name('clubs.syncLeague');
    Route::post('/clubs/{club}/toggle-active', [ClubManagementController::class, 'toggleActive'])->name('clubs.toggleActive');
    Route::delete('/clubs/{club}', [ClubManagementController::class, 'destroy'])->name('clubs.destroy');

    // Competition Management (API-based)
    Route::get('/competitions', [CompetitionManagementController::class, 'index'])->name('competitions.index');
    Route::get('/competitions/{id}', [CompetitionManagementController::class, 'show'])->name('competitions.show')->where('id', '[0-9]+');

    // Analytics
    Route::get('/analytics', AnalyticsController::class)->name('analytics');

    // Ad Management
    Route::get('/ads', [AdManagementController::class, 'index'])->name('ads.index');
    Route::get('/ads/create', [AdManagementController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdManagementController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}/edit', [AdManagementController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{ad}', [AdManagementController::class, 'update'])->name('ads.update');
    Route::post('/ads/{ad}/toggle', [AdManagementController::class, 'toggle'])->name('ads.toggle');
    Route::delete('/ads/{ad}', [AdManagementController::class, 'destroy'])->name('ads.destroy');
});

// ── Ad Click Tracking ──────────────────────────────────
Route::post('/ad/{ad}/click', function (\App\Models\Ad $ad) {
    $ad->increment('click_count');

    return response()->noContent();
})->name('ad.click');

require __DIR__.'/settings.php';
