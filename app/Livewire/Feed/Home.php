<?php

namespace App\Livewire\Feed;

use App\Models\Post;
use Livewire\Component;

class Home extends Component
{
    public string $newPostBody = '';

    public string $newPostType = 'banter';

    public string $sharingPostUrl = '';

    public ?int $matchId = null;

    public array $postTypes = [
        'banter' => ['icon' => '💬', 'label' => 'Banter'],
        'match_reaction' => ['icon' => '⚽', 'label' => 'Match Reaction'],
        'goal_reaction' => ['icon' => '🥅', 'label' => 'Goal Reaction'],
        'tactical_opinion' => ['icon' => '📋', 'label' => 'Tactical Opinion'],
        'player_comparison' => ['icon' => '⚔', 'label' => 'Player Comparison'],
        'meme' => ['icon' => '😂', 'label' => 'Meme'],
        'breaking_news' => ['icon' => '📰', 'label' => 'Breaking News'],
        'matchday_discussion' => ['icon' => '🔥', 'label' => 'Matchday Discussion'],
    ];

    public function mount(): void
    {
        // Default to banter type for football-first feed
    }

    public function render()
    {
        $user = auth()->user();

        // Get feed: posts from followed users + trending + match reactions
        $feed = Post::with(['user', 'community', 'likes', 'comments'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->where(function ($q) use ($user) {
                if ($user) {
                    $followingIds = $user->following()->pluck('users.id')->toArray();
                    $q->whereIn('user_id', $followingIds)
                        ->orWhere('user_id', $user->id);
                }
                // Always include trending and match-related posts
                $q->orWhere('is_pinned', true)
                    ->orWhere('type', 'match_reaction')
                    ->orWhere('type', 'goal_reaction')
                    ->orWhere('type', 'breaking_news');
            })
            ->latest()
            ->limit(50)
            ->get();

        // Get active polls
        $activePolls = \App\Models\Poll::with('options')
            ->active()
            ->latest()
            ->take(3)
            ->get();

        // Get trending hashtags from real posts
        $trendingTopics = $this->getTrendingHashtags();

        return view('livewire.feed.home', [
            'feed' => $feed,
            'activePolls' => $activePolls,
            'trendingTopics' => $trendingTopics,
            'user' => $user,
        ]);
    }

    /**
     * Extract trending hashtags from recent posts
     */
    private function getTrendingHashtags(): array
    {
        $recentPosts = \App\Models\Post::approved()
            ->where('created_at', '>=', now()->subDays(3))
            ->pluck('body');

        $hashtags = [];

        foreach ($recentPosts as $body) {
            preg_match_all('/#(\w+)/', $body, $matches);
            if (! empty($matches[1])) {
                foreach ($matches[1] as $tag) {
                    $tag = strtolower($tag);
                    $hashtags[$tag] = ($hashtags[$tag] ?? 0) + 1;
                }
            }
        }

        arsort($hashtags);

        return collect($hashtags)->take(5)->map(function ($count, $tag) {
            return [
                'tag' => '#'.ucfirst($tag),
                'count' => $count,
            ];
        })->values()->toArray();
    }

    public function postAction()
    {
        $this->validate([
            'newPostBody' => 'required|string|max:280',
        ]);

        if (! auth()->check()) {
            return;
        }

        Post::create([
            'user_id' => auth()->id(),
            'body' => $this->newPostBody,
            'type' => $this->newPostType,
            'match_id' => $this->matchId,
            'is_approved' => true,
        ]);

        $this->newPostBody = '';
        $this->dispatch('post-created');
    }

    public function likePost(int $postId)
    {
        if (! auth()->check()) {
            return;
        }

        $post = Post::find($postId);
        if ($post && ! auth()->user()->hasLiked($post)) {
            $post->likes()->create(['user_id' => auth()->id()]);
            $post->increment('likes_count');
        }
    }

    public function sharePost(int $postId)
    {
        if (! auth()->check()) {
            return;
        }

        $post = Post::find($postId);
        if ($post) {
            $this->sharingPostUrl = route('posts.show', $post);

            $post->shares()->create(['user_id' => auth()->id()]);
            $post->increment('shares_count');

            // award points
            $gamification = app(\App\Services\GamificationService::class);
            $gamification->awardPoints(auth()->user(), 'share_created', $post);

            $this->modal('share-post')->show();
        }
    }

    public function getListeners(): array
    {
        return [
            'post-created' => '$refresh',
            'echo:feed,PostCreated' => '$refresh',
        ];
    }
}
