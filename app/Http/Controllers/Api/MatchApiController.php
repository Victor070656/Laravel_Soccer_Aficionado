<?php

namespace App\Http\Controllers\Api;

use App\Models\FootballMatch;
use Illuminate\Http\Request;

class MatchApiController extends BaseApiController
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

        return $this->success($matches);
    }

    public function show(FootballMatch $match)
    {
        $match->load([
            'homeClub',
            'awayClub',
            'competition',
            'events.player',
            'events.secondaryPlayer',
        ]);

        return $this->success($match);
    }

    public function live()
    {
        $matches = FootballMatch::with(['homeClub', 'awayClub', 'competition', 'events'])
            ->live()
            ->get();

        return $this->success($matches);
    }
}
