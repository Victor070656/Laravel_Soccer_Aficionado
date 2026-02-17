<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Badge;
use App\Models\User;

class GamificationService
{
    /**
     * Points awarded per action type.
     */
    protected array $pointsMap = [
        'post_created' => 10,
        'comment_created' => 5,
        'like_received' => 2,
        'vote_cast' => 3,
        'share_created' => 5,
        'community_joined' => 5,
        'follow_gained' => 2,
    ];

    /**
     * Award points to a user for a given action.
     */
    public function awardPoints(User $user, string $action, $pointable): void
    {
        $points = $this->pointsMap[$action] ?? 0;

        if ($points <= 0) {
            return;
        }

        $user->awardPoints($points, $action, $pointable);

        $this->checkBadges($user);
    }

    /**
     * Check and award badges to a user based on their activity.
     * Optimised: caches all badges, fetches earned IDs in one query,
     * pre-loads counts needed for qualification checks.
     */
    public function checkBadges(User $user): void
    {
        $badges = cache()->remember('all_badges', 3600, fn() => Badge::all());

        $earnedBadgeIds = $user->badges()->pluck('badge_id')->all();

        // Pre-load counts for qualification checks (single query each)
        $counts = [
            'posts_count' => null,
            'comments_count' => null,
            'votes_count' => null,
            'followers_count' => null,
            'communities_count' => null,
        ];

        foreach ($badges as $badge) {
            if (in_array($badge->id, $earnedBadgeIds)) {
                continue;
            }

            if ($this->qualifiesForBadge($user, $badge, $counts)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
    }

    protected function qualifiesForBadge(User $user, Badge $badge, array &$counts): bool
    {
        // Check points requirement
        if ($badge->points_required > 0 && $user->points < $badge->points_required) {
            return false;
        }

        // Check specific criteria
        if ($badge->criteria && $badge->criteria_value > 0) {
            $key = $badge->criteria;

            // Lazy-load count only when first needed
            if (array_key_exists($key, $counts) && $counts[$key] === null) {
                $counts[$key] = match ($key) {
                    'posts_count' => $user->posts()->count(),
                    'comments_count' => $user->comments()->count(),
                    'votes_count' => $user->votes()->count(),
                    'followers_count' => $user->followers()->count(),
                    'communities_count' => $user->communities()->count(),
                    default => 0,
                };
            }

            $count = $counts[$key] ?? 0;
            return $count >= $badge->criteria_value;
        }

        return $badge->points_required > 0;
    }

    /**
     * Get leaderboard of top users.
     */
    public function getLeaderboard(int $limit = 20)
    {
        return User::where('is_banned', false)
            ->orderByDesc('points')
            ->take($limit)
            ->get(['id', 'name', 'username', 'avatar', 'points']);
    }

    /**
     * Record an activity for the activity feed.
     */
    public function recordActivity(User $user, string $type, $subject, ?array $data = null): void
    {
        Activity::create([
            'user_id' => $user->id,
            'type' => $type,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'data' => $data,
        ]);
    }
}
