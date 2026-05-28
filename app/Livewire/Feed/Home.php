<?php

namespace App\Livewire\Feed;

use App\Actions\Posts\CreatePost;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public string $sharingPostUrl = '';

    public array $postTypes = [];

    public function mount(): void
    {
        $this->postTypes = CreatePost::types();
    }

    public function render()
    {
        $user = Auth::user();

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
            if (!empty($matches[1])) {
                foreach ($matches[1] as $tag) {
                    $tag = strtolower($tag);
                    $hashtags[$tag] = ($hashtags[$tag] ?? 0) + 1;
                }
            }
        }

        arsort($hashtags);

        return collect($hashtags)->take(5)->map(function ($count, $tag) {
            return [
                'tag' => '#' . ucfirst($tag),
                'count' => $count,
            ];
        })->values()->toArray();
    }

    public function sharePost(int $postId)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if (!$user) {
            return;
        }

        $post = Post::find($postId);
        if ($post) {
            $this->sharingPostUrl = route('posts.show', $post);

            $post->shares()->create(['user_id' => Auth::id()]);
            $post->increment('shares_count');

            // award points
            $gamification = app(\App\Services\GamificationService::class);
            $gamification->awardPoints($user, 'share_created', $post);

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
