<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Community;
use App\Models\FootballMatch;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');

        $results = [
            'users' => collect(),
            'clubs' => collect(),
            'communities' => collect(),
            'posts' => collect(),
            'matches' => collect(),
        ];

        if (empty($query)) {
            $users = $results['users'];
            $clubs = $results['clubs'];
            $communities = $results['communities'];
            $posts = $results['posts'];
            $matches = $results['matches'];
            return view('search.index', compact('users', 'clubs', 'communities', 'posts', 'matches', 'query', 'type'));
        }

        if ($type === 'all' || $type === 'users') {
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'clubs') {
            $results['clubs'] = Club::where('is_active', true)
                ->where(fn($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('country', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'communities') {
            $results['communities'] = Community::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'posts') {
            $results['posts'] = Post::approved()
                ->where('body', 'like', "%{$query}%")
                ->with('user')
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'matches') {
            $results['matches'] = FootballMatch::with(['homeClub', 'awayClub'])
                ->whereHas('homeClub', fn($q) => $q->where('name', 'like', "%{$query}%"))
                ->orWhereHas('awayClub', fn($q) => $q->where('name', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        $users = $results['users'];
        $clubs = $results['clubs'];
        $communities = $results['communities'];
        $posts = $results['posts'];
        $matches = $results['matches'];

        return view('search.index', compact('users', 'clubs', 'communities', 'posts', 'matches', 'query', 'type'));
    }
}
