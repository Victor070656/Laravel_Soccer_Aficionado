<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\FootballMatch;
use App\Models\Club;
use Illuminate\Http\Request;

class MatchManagementController extends Controller
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

        $matches = $query->orderByDesc('kick_off')->paginate(25);
        $competitions = Competition::where('is_active', true)->get();

        return view('admin.matches.index', compact('matches', 'competitions'));
    }

    public function create()
    {
        $clubs = Club::where('is_active', true)->orderBy('name')->select(['id', 'name'])->get();
        $competitions = Competition::where('is_active', true)->select(['id', 'name'])->get();

        return view('admin.matches.create', compact('clubs', 'competitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'home_club_id' => 'required|exists:clubs,id|different:away_club_id',
            'away_club_id' => 'required|exists:clubs,id',
            'kick_off' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'matchday' => 'nullable|integer|min:1',
            'round' => 'nullable|string|max:50',
            'season' => 'nullable|string|max:20',
            'referee' => 'nullable|string|max:255',
        ]);

        FootballMatch::create($validated);

        return redirect()->route('admin.matches.index')->with('success', 'Match created!');
    }

    public function edit(FootballMatch $match)
    {
        $clubs = Club::where('is_active', true)->orderBy('name')->select(['id', 'name'])->get();
        $competitions = Competition::where('is_active', true)->select(['id', 'name'])->get();

        return view('admin.matches.edit', compact('match', 'clubs', 'competitions'));
    }

    public function update(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'home_club_id' => 'required|exists:clubs,id|different:away_club_id',
            'away_club_id' => 'required|exists:clubs,id',
            'kick_off' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,live,half_time,finished,postponed,cancelled',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'home_score_ht' => 'nullable|integer|min:0',
            'away_score_ht' => 'nullable|integer|min:0',
            'matchday' => 'nullable|integer|min:1',
            'round' => 'nullable|string|max:50',
            'season' => 'nullable|string|max:20',
            'attendance' => 'nullable|integer|min:0',
            'referee' => 'nullable|string|max:255',
        ]);

        $match->update($validated);

        return redirect()->route('admin.matches.index')->with('success', 'Match updated!');
    }

    public function destroy(FootballMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', 'Match deleted.');
    }
}
