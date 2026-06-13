<?php

namespace App\Livewire\Communities;

use App\Models\Community;
use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Community $community;

    public function mount(Community $community): void
    {
        $this->community = $community->load(['club', 'members', 'posts'])->loadCount(['members', 'posts']);
    }

    public function render()
    {
        $discussions = Post::where('community_id', $this->community->id)
            ->with(['user', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(10)
            ->get();

        $topMembers = $this->community->members()
            ->withCount(['posts', 'comments'])
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        return view('livewire.communities.show', [
            'discussions' => $discussions,
            'topMembers' => $topMembers,
            'isMember' => auth()->check() && $this->community->members()->where('user_id', auth()->id())->exists(),
            'isModerator' => auth()->check() && $this->community->isModerator(auth()->user()),
        ]);
    }

    public function join()
    {
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        if ($user->isMemberOf($this->community)) {
            $this->community->members()->detach($user->id);
            $this->community->decrement('members_count');
        } else {
            $this->community->members()->attach($user->id, ['role' => 'member']);
            $this->community->increment('members_count');

            // award points
            $gamification = app(\App\Services\GamificationService::class);
            $gamification->awardPoints($user, 'community_joined', $this->community);
            $gamification->recordActivity($user, 'community_joined', $this->community);
        }

        $this->community->refresh();
        $this->community->loadCount(['members', 'posts']);
    }

    public function handlePostCreated()
    {
        $this->community->loadCount('posts');
    }

    public function getListeners(): array
    {
        return [
            'echo:community.{community.id},CommunityUpdated' => '$refresh',
            'post-created' => 'handlePostCreated',
        ];
    }
}
