<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        $query = Club::where('is_active', true);

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $clubs = $query->withCount('fans')->orderBy('name')->paginate(24);

        return view('clubs.index', compact('clubs'));
    }

    public function show(Club $club)
    {
        $club->load(['players' => fn($q) => $q->where('is_active', true)->orderBy('position')]);
        $club->loadCount('fans');

        $recentMatches = $club->getAllMatches()
            ->with(['homeClub', 'awayClub', 'competition'])
            ->finished()
            ->take(5)
            ->get();

        $upcomingMatches = $club->getAllMatches()
            ->with(['homeClub', 'awayClub', 'competition'])
            ->upcoming()
            ->take(5)
            ->get();

        $communities = $club->communities()->where('is_active', true)->withCount('members')->get();

        return view('clubs.show', compact('club', 'recentMatches', 'upcomingMatches', 'communities'));
    }

    public function toggleFavorite(Request $request, Club $club)
    {
        $user = $request->user();

        if ($user->favoriteClubs()->where('club_id', $club->id)->exists()) {
            $user->favoriteClubs()->detach($club->id);
            $message = 'Club removed from favorites.';
        } else {
            $user->favoriteClubs()->attach($club->id);
            $message = 'Club added to favorites!';
        }

        return back()->with('success', $message);
    }
}
