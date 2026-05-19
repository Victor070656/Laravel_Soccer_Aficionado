<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load([
            'favoriteClubs',
            'favoritePlayer.club',
            'badges',
            'posts' => fn ($query) => $query->latest()->limit(5),
            'comments' => fn ($query) => $query->latest()->limit(5),
            'followers',
            'following',
            'communities',
        ]);
    }

    public function getFanRankProperty(): string
    {
        $rank = $this->user->points ?? 0;

        return match (true) {
            $rank >= 10000 => 'Legendary Fan',
            $rank >= 5000 => 'Elite Fan',
            $rank >= 2500 => 'Super Fan',
            $rank >= 1000 => 'Die-hard Fan',
            $rank >= 500 => 'Active Fan',
            $rank >= 100 => 'Rising Fan',
            default => 'New Fan',
        };
    }

    public function getFanRankColorProperty(): string
    {
        $rank = $this->user->points ?? 0;

        return match (true) {
            $rank >= 10000 => 'text-primary-container',
            $rank >= 5000 => 'text-tertiary-fixed-dim',
            $rank >= 2500 => 'text-secondary',
            $rank >= 1000 => 'text-on-surface',
            $rank >= 500 => 'text-on-surface-variant',
            default => 'text-outline',
        };
    }

    public function getPrimaryClubProperty(): ?\App\Models\Club
    {
        return $this->user->favoriteClubs->where('pivot.is_primary', true)->first()
            ?? $this->user->favoriteClubs->first();
    }

    public function getMatchdayStreakProperty(): int
    {
        // Calculate consecutive days with match-related activity
        return $this->user->activities()
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn('type', ['match_view', 'match_reaction', 'prediction'])
            ->get()
            ->groupBy(fn ($activity) => $activity->created_at->toDateString())
            ->count();
    }

    public function getEngagementScoreProperty(): int
    {
        $posts = $this->user->posts()->count();
        $comments = $this->user->comments()->count();
        $likes = $this->user->likes()->count();

        return (int) ($posts * 5 + $comments * 2 + $likes * 1);
    }

    public function render()
    {
        return view('livewire.profile.show', [
            'recentPosts' => $this->user->posts()->latest()->limit(5)->get(),
            'recentComments' => $this->user->comments()->latest()->limit(5)->with('post')->get(),
            'badges' => $this->user->badges()->limit(10)->get(),
            'topCommunities' => $this->user->communities()->limit(5)->get(),
        ]);
    }
}
