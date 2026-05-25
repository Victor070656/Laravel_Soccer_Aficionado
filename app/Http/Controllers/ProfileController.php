<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {}

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

        $isFollowing = auth()->check() && auth()->user()->isFollowing($user);

        return view('profiles.show', compact('user', 'posts', 'isFollowing'));
    }

    public function follow(Request $request, User $user)
    {
        $follower = $request->user();

        if ($follower->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($follower->isFollowing($user)) {
            $follower->following()->detach($user->id);

            return back()->with('success', 'Unfollowed.');
        }

        $follower->following()->attach($user->id);

        $this->gamification->awardPoints($user, 'follow_gained', $follower);
        $this->gamification->recordActivity($follower, 'user_followed', $user);
        $this->notifications->notifyFollow($follower, $user);

        return back()->with('success', 'Following!');
    }
}
