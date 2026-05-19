<?php

namespace App\Livewire\Communities;

use App\Models\Community;
use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Community $community;

    public function mount(int $id): void
    {
        $this->community = Community::with(['club', 'members', 'posts'])->findOrFail($id);
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

        if ($this->community->members()->where('user_id', auth()->id())->exists()) {
            $this->community->members()->detach(auth()->id());
        } else {
            $this->community->members()->attach(auth()->id(), ['role' => 'member']);
        }
    }

    public function getListeners(): array
    {
        return [
            'echo:community.{community.id},CommunityUpdated' => '$refresh',
        ];
    }
}
