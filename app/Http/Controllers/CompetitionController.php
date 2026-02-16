<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::where('is_active', true)
            ->withCount('clubs')
            ->orderBy('name')
            ->paginate(20);

        return view('competitions.index', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $competition->load('clubs');

        $standings = $competition->currentStandings()
            ->with('club')
            ->get();

        $upcomingMatches = $competition->matches()
            ->with(['homeClub', 'awayClub'])
            ->upcoming()
            ->take(10)
            ->get();

        $recentResults = $competition->matches()
            ->with(['homeClub', 'awayClub'])
            ->finished()
            ->take(10)
            ->get();

        return view('competitions.show', compact('competition', 'standings', 'upcomingMatches', 'recentResults'));
    }
}
