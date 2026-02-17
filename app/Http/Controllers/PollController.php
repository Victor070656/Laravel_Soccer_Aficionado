<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
    ) {
    }

    public function index()
    {
        $polls = Poll::with(['options', 'user', 'match.homeClub', 'match.awayClub'])
            ->active()
            ->latest()
            ->paginate(20);

        return view('polls.index', compact('polls'));
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => fn($q) => $q->orderByDesc('votes_count'), 'user', 'match.homeClub', 'match.awayClub']);

        $userVote = null;
        if (auth()->check()) {
            $userVote = $poll->votes()->where('user_id', auth()->id())->first();
        }

        return view('polls.show', compact('poll', 'userVote'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:general,motm,prediction,gotw',
            'match_id' => 'nullable|exists:matches,id', // FootballMatch uses 'matches' table
            'closes_at' => 'nullable|date|after:now',
            'options' => 'required|array|min:2|max:10',
            'options.*.label' => 'required|string|max:255',
            'options.*.player_id' => 'nullable|exists:players,id',
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
                'player_id' => $option['player_id'] ?? null,
            ]);
        }

        return redirect()->route('polls.show', $poll)->with('success', 'Poll created!');
    }

    public function vote(Request $request, Poll $poll)
    {
        $user = $request->user();

        if (!$poll->isOpen()) {
            return back()->with('error', 'This poll is closed.');
        }

        if ($user->hasVotedOn($poll)) {
            return back()->with('error', 'You have already voted on this poll.');
        }

        $validated = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::findOrFail($validated['poll_option_id']);

        if ($option->poll_id !== $poll->id) {
            return back()->with('error', 'Invalid option.');
        }

        $vote = $user->votes()->create([
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
        ]);

        $option->increment('votes_count');
        $poll->increment('total_votes');

        $this->gamification->awardPoints($user, 'vote_cast', $vote);

        return back()->with('success', 'Vote recorded!');
    }
}
