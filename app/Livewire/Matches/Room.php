<?php

namespace App\Livewire\Matches;

use App\Models\MatchAction;
use App\Models\MatchComment;
use App\Services\FootballApiService;
use Livewire\Component;

class Room extends Component
{
    public int $matchId;

    public string $newComment = '';

    public array $availableEmojis = [];

    public array $recentEmojiStorm = [];

    public function mount(int $id): void
    {
        $this->matchId = $id;
        $this->availableEmojis = MatchAction::getAvailableEmojis();
    }

    public function render(FootballApiService $api)
    {
        $raw = $api->getFixture($this->matchId);

        if (! $raw) {
            abort(404, 'Match not found.');
        }

        $match = (object) FootballApiService::normaliseFixture($raw);
        $events = collect($api->getFixtureEvents($this->matchId))
            ->map(fn (array $e) => (object) FootballApiService::normaliseEvent($e));

        // Local comments and reactions
        $comments = MatchComment::where('match_id', $this->matchId)
            ->with('user')
            ->latest()
            ->limit(50)
            ->get();

        $reactions = MatchAction::where('match_id', $this->matchId)
            ->with('user')
            ->latest()
            ->limit(20)
            ->get();

        // Emoji strom tracker (for animation)
        $this->recentEmojiStorm = MatchAction::where('match_id', $this->matchId)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->get()
            ->groupBy('emoji')
            ->map(fn ($group) => $group->count())
            ->sortDesc()
            ->take(3)
            ->toArray();

        // Heat meter: comments + reactions in last 60 seconds
        $heatCount = MatchComment::where('match_id', $this->matchId)
            ->where('created_at', '>=', now()->subSeconds(60))
            ->count()
            + MatchAction::where('match_id', $this->matchId)
                ->where('created_at', '>=', now()->subSeconds(60))
                ->count();

        $heatLevel = match (true) {
            $heatCount >= 30 => 'EXTREME',
            $heatCount >= 20 => 'HIGH',
            $heatCount >= 10 => 'MEDIUM',
            $heatCount >= 5 => 'LOW',
            default => 'CALM',
        };

        // Fan momentum: count comments by club support
        $homeMomentum = MatchComment::where('match_id', $this->matchId)
            ->whereHas('user.favoriteClubs', function ($q) use ($match) {
                $q->where('clubs.id', $match->home_team['id'] ?? 0);
            })
            ->count();

        $awayMomentum = MatchComment::where('match_id', $this->matchId)
            ->whereHas('user.favoriteClubs', function ($q) use ($match) {
                $q->where('clubs.id', $match->away_team['id'] ?? 0);
            })
            ->count();

        return view('livewire.matches.room', [
            'match' => $match,
            'events' => $events,
            'comments' => $comments,
            'reactions' => $reactions,
            'heatLevel' => $heatLevel,
            'heatCount' => $heatCount,
            'homeMomentum' => $homeMomentum,
            'awayMomentum' => $awayMomentum,
        ]);
    }

    public function postComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:280',
        ]);

        if (! auth()->check()) {
            return;
        }

        MatchComment::create([
            'match_id' => $this->matchId,
            'user_id' => auth()->id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->dispatch('comment-added');
    }

    public function react(string $emoji)
    {
        if (! auth()->check()) {
            return;
        }

        MatchAction::create([
            'match_id' => $this->matchId,
            'user_id' => auth()->id(),
            'emoji' => $emoji,
        ]);

        $this->dispatch('reaction-added');
    }

    public function getListeners(): array
    {
        return [
            "echo:match.{$this->matchId},.MatchRoomUpdated" => '$refresh',
        ];
    }
}
