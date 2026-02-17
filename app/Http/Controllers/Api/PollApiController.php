<?php

namespace App\Http\Controllers\Api;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollApiController extends BaseApiController
{
    public function index()
    {
        $polls = Poll::with(['options', 'user', 'match.homeClub', 'match.awayClub'])
            ->active()
            ->latest()
            ->paginate(20);

        return $this->success($polls);
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => fn($q) => $q->orderByDesc('votes_count'), 'user', 'match.homeClub', 'match.awayClub']);

        return $this->success($poll);
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

        $user->votes()->create([
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
        ]);

        $option->increment('votes_count');
        $poll->increment('total_votes');

        return $this->success(null, 'Vote recorded.');
    }
}
