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

    public function edit()
    {
        $user = auth()->user();

        return view('profiles.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:255',
            'favorite_player_id' => 'nullable|integer',
            'favorite_coach' => 'nullable|string|max:255',
            'football_personality' => 'nullable|string|max:255',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = '/storage/'.$path;
        }

        $user->update($validated);

        return redirect()->route('profiles.show', $user)->with('success', 'Profile updated successfully!');
    }
}
