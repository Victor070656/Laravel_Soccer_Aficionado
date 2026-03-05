<?php

namespace App\Http\Controllers\Api;

use App\Models\Poll;
use App\Models\PollOption;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class PollApiController extends BaseApiController
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

        // Append per-user vote state and alias 'user' as 'created_by'
        $authUser = auth('sanctum')->user();
        $polls->getCollection()->transform(function ($poll) use ($authUser) {
            $vote = $authUser
                ? $poll->votes()->where('user_id', $authUser->id)->first()
                : null;
            $poll->user_vote = $vote?->poll_option_id ?? null;
            $poll->created_by = $poll->user;
            $poll->options->each(fn ($opt) => $opt->setRelation('poll', $poll));
            return $poll;
        });

        return $this->success($polls);
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => fn($q) => $q->orderByDesc('votes_count'), 'user', 'match.homeClub', 'match.awayClub']);

        $userVote = null;
        if (auth('sanctum')->check()) {
            $userVote = $poll->votes()->where('user_id', auth('sanctum')->id())->first();
        }

        $poll->created_by = $poll->user;
        $poll->options->each(fn ($opt) => $opt->setRelation('poll', $poll));

        return $this->success([
            'poll' => $poll,
            'user_vote' => $userVote,
        ]);
    }

    public function vote(Request $request, Poll $poll)
    {
        $user = $request->user();

        if (!$poll->isOpen()) {
            return $this->error('This poll is closed.', 422);
        }

        if ($user->hasVotedOn($poll)) {
            return $this->error('Already voted.', 422);
        }

        $validated = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::findOrFail($validated['poll_option_id']);

        if ($option->poll_id !== $poll->id) {
            return $this->error('Invalid option.', 422);
        }

        $vote = $user->votes()->create([
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
        ]);

        $option->increment('votes_count');
        $poll->increment('total_votes');

        $this->gamification->awardPoints($user, 'vote_cast', $vote);

        return $this->success(null, 'Vote recorded.');
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

        $poll->load(['options', 'user']);

        return $this->success($poll, 'Poll created!', 201);
    }
}
