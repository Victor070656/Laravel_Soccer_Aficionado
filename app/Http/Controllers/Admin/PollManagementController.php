<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\FootballMatch;
use Illuminate\Http\Request;

class PollManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Poll::with(['user', 'match.homeClub', 'match.awayClub'])
            ->withCount('votes');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->where('is_active', true)->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>', now())),
                'closed' => $query->where(fn($q) => $q->where('is_active', false)->orWhere('closes_at', '<=', now())),
                default => null,
            };
        }

        $polls = $query->latest()->paginate(25);

        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        $matches = FootballMatch::with(['homeClub', 'awayClub'])
            ->where('status', '!=', 'finished')
            ->orderBy('kick_off')
            ->get();

        return view('admin.polls.create', compact('matches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:general,motm,prediction,gotw',
            'match_id' => 'nullable|exists:matches,id',
            'closes_at' => 'nullable|date|after:now',
            'options' => 'required|array|min:2|max:10',
            'options.*.label' => 'required|string|max:255',
        ]);

        $poll = $request->user()->polls()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'match_id' => $validated['match_id'] ?? null,
            'closes_at' => $validated['closes_at'] ?? null,
        ]);

        foreach ($validated['options'] as $option) {
            $poll->options()->create([
                'label' => $option['label'],
            ]);
        }

        return redirect()->route('admin.polls.index')->with('success', 'Poll created!');
    }

    public function close(Poll $poll)
    {
        $poll->update(['is_active' => false]);

        return back()->with('success', 'Poll closed.');
    }

    public function reopen(Poll $poll)
    {
        $poll->update(['is_active' => true]);

        return back()->with('success', 'Poll reopened.');
    }

    public function destroy(Poll $poll)
    {
        $poll->votes()->delete();
        $poll->options()->delete();
        $poll->delete();

        return redirect()->route('admin.polls.index')->with('success', 'Poll deleted.');
    }
}
