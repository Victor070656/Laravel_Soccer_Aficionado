<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Like;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __invoke()
    {
        // User growth (last 30 days)
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Post activity (last 30 days)
        $postActivity = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Engagement metrics
        $engagement = [
            'total_users' => User::count(),
            'active_users_7d' => User::where('updated_at', '>=', now()->subDays(7))->count(),
            'active_users_30d' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            'total_posts' => Post::count(),
            'posts_today' => Post::whereDate('created_at', today())->count(),
            'posts_this_week' => Post::where('created_at', '>=', now()->subWeek())->count(),
            'total_comments' => Comment::count(),
            'comments_today' => Comment::whereDate('created_at', today())->count(),
            'total_likes' => Like::count(),
            'likes_today' => Like::whereDate('created_at', today())->count(),
            'total_votes' => Vote::count(),
            'total_communities' => Community::count(),
            'active_polls' => Poll::where('is_active', true)->count(),
            'total_reports' => Report::count(),
            'pending_reports' => Report::pending()->count(),
            'resolved_reports' => Report::where('status', '!=', 'pending')->count(),
            'banned_users' => User::where('is_banned', true)->count(),
        ];

        // Top communities by members
        $topCommunities = Community::orderByDesc('members_count')
            ->take(10)
            ->get(['id', 'name', 'members_count', 'slug']);

        // Most active users (by points)
        $topUsers = User::orderByDesc('points')
            ->take(10)
            ->get(['id', 'name', 'username', 'points']);

        // Recent reports breakdown
        $reportsByReason = Report::selectRaw('reason, COUNT(*) as count')
            ->groupBy('reason')
            ->orderByDesc('count')
            ->pluck('count', 'reason')
            ->toArray();

        // Content moderation stats
        $moderationStats = [
            'pending_posts' => Post::where('is_approved', false)->count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
            'approved_posts' => Post::where('is_approved', true)->count(),
            'approved_comments' => Comment::where('is_approved', true)->count(),
        ];

        return view('admin.analytics.index', compact(
            'userGrowth',
            'postActivity',
            'engagement',
            'topCommunities',
            'topUsers',
            'reportsByReason',
            'moderationStats',
        ));
    }
}
