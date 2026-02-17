<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Community;
use App\Models\FootballMatch;
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
            'clubs' => Club::count(),
            'communities' => Community::count(),
            'matches' => FootballMatch::count(),
            'pending_reports' => Report::pending()->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
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
