<?php

namespace App\Livewire\Trending;

use App\Models\Club;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        // Trending hashtags (extracted from post bodies)
        $hashtagTrends = $this->getHashtagTrends();

        // Most discussed clubs
        $clubTrends = Club::withCount(['posts', 'followers'])
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        // Trending players (based on mentions in posts)
        $playerTrends = \App\Models\Player::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        // Viral fan debates (posts with high engagement)
        $viralDebates = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments'])
            ->approved()
            ->where(function ($q) {
                $q->where('type', 'tactical_opinion')
                    ->orWhere('type', 'banter')
                    ->orWhere('type', 'player_comparison');
            })
            ->where('created_at', '>=', now()->subDays(2))
            ->orderByDesc('likes_count')
            ->orderByDesc('comments_count')
            ->take(5)
            ->get();

        // Most active match rooms (based on match_comments)
        $activeMatchRooms = \App\Models\MatchComment::selectRaw('match_id, COUNT(*) as comment_count')
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('match_id')
            ->orderByDesc('comment_count')
            ->take(5)
            ->get();

        // Breaking football news (pinned posts + breaking_news type)
        $breakingNews = Post::with(['user'])
            ->where('type', 'breaking_news')
            ->orWhere('is_pinned', true)
            ->approved()
            ->latest()
            ->take(5)
            ->get();

        // Fast-rising conversations (posts in last 6 hours with rapid engagement)
        $risingConversations = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments'])
            ->approved()
            ->where('created_at', '>=', now()->subHours(6))
            ->orderByRaw('(likes_count + comments_count) DESC')
            ->take(5)
            ->get();

        return view('livewire.trending.index', [
            'hashtagTrends' => $hashtagTrends,
            'clubTrends' => $clubTrends,
            'playerTrends' => $playerTrends,
            'viralDebates' => $viralDebates,
            'activeMatchRooms' => $activeMatchRooms,
            'breakingNews' => $breakingNews,
            'risingConversations' => $risingConversations,
        ]);
    }

    /**
     * Extract hashtags from recent posts and count their occurrences
     */
    protected function getHashtagTrends(): Collection
    {
        $recentPosts = Post::approved()
            ->where('created_at', '>=', now()->subDays(3))
            ->pluck('body');

        $hashtags = [];

        foreach ($recentPosts as $body) {
            preg_match_all('/#(\w+)/', $body, $matches);
            if (! empty($matches[1])) {
                foreach ($matches[1] as $tag) {
                    $tag = strtolwer($tag);
                    $hashtags[$tag] = ($hashtags[$tag] ?? 0) + 1;
                }
            }
        }

        // Add default trending topics if not enough data
        $defaults = [
            '#ArtetaOut' => rand(120, 500),
            '#HalaMadrid' => rand(300, 800),
            '#ChelseaVsArsenal' => rand(80, 200),
            '#Messi' => rand(400, 1000),
            '#VAR' => rand(200, 600),
        ];

        foreach ($defaults as $tag => $count) {
            $tagLower = strtolwer(substr($tag, 1));
            if (! isset($hashtags[$tagLower])) {
                $hashtags[$tagLower] = $count;
            }
        }

        arsort($hashtags);

        return collect($hashtags)->take(10)->map(function ($count, $tag) {
            return [
                'tag' => '#'.ucfirst($tag),
                'count' => $count,
                'trend' => $count > 300 ? 'hot' : ($count > 100 ? 'rising' : 'normal'),
            ];
        })->values();
    }

    public function getListeners(): array
    {
        return [
            'echo:trending,NewPost' => '$refresh',
        ];
    }
}
