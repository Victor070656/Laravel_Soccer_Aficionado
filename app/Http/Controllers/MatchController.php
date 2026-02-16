<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Competition;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = FootballMatch::with(['homeClub', 'awayClub', 'competition']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('kick_off', $request->date);
        }

        $matches = $query->orderByDesc('kick_off')->paginate(20);
        $competitions = Competition::where('is_active', true)->get();

        return view('matches.index', compact('matches', 'competitions'));
    }

    public function show(FootballMatch $match)
    {
        $match->load([
            'homeClub.players',
            'awayClub.players',
            'competition',
            'events.player',
            'events.secondaryPlayer',
            'polls.options',
        ]);

        return view('matches.show', compact('match'));
    }

    public function live()
    {
        $matches = FootballMatch::with(['homeClub', 'awayClub', 'competition', 'events'])
            ->live()
            ->get();

        return view('matches.live', compact('matches'));
    }
}
