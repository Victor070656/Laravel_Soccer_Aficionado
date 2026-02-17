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
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'users' => User::count(),
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'communities' => Community::count(),
            'polls' => Poll::count(),
            'likes' => Like::count(),
            'pending_reports' => Report::pending()->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'pending_posts' => Post::where('is_approved', false)->count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
            'active_polls' => Poll::where('is_active', true)->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>', now()))->count(),
        ];

        $recentReports = Report::with(['reporter', 'reportable'])
            ->pending()
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::with('roles')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentReports', 'recentUsers'));
    }
}
