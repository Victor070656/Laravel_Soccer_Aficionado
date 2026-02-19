<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ProfileApiController extends BaseApiController
{
    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {
    }

    public function show(User $user)
    {
        $user->loadCount(['followers', 'following', 'posts']);
        $user->load(['favoriteClubs', 'badges', 'communities']);

        $posts = $user->posts()
            ->with(['community', 'user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->latest()
            ->paginate(20);

        $isFollowing = auth('sanctum')->check() && auth('sanctum')->user()->isFollowing($user);

        return $this->success([
            'user' => $user,
            'posts' => $posts,
            'is_following' => $isFollowing,
        ]);
    }

    public function follow(Request $request, User $user)
    {
        $follower = $request->user();

        if ($follower->id === $user->id) {
            return $this->error('You cannot follow yourself.', 422);
        }

        if ($follower->isFollowing($user)) {
            $follower->following()->detach($user->id);

            return $this->success(['following' => false], 'Unfollowed.');
        }

        $follower->following()->attach($user->id);

        $this->gamification->awardPoints($user, 'follow_gained', $follower);
        $this->gamification->recordActivity($follower, 'user_followed', $user);
        $this->notifications->notifyFollow($follower, $user);

        return $this->success(['following' => true], 'Following!');
    }
}
