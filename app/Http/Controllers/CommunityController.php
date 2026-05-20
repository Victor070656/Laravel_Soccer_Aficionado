<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {}

    public function index()
    {
        return view('communities.index');
    }

    public function show(Community $community)
    {
        $this->authorize('view', $community);

        return view('communities.show', compact('community'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Community::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'rules' => 'nullable|string|max:5000',
            'club_id' => 'nullable|exists:clubs,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ]);

        $community = Community::create([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'rules' => $validated['rules'] ?? null,
            'club_id' => $validated['club_id'] ?? null,
            'created_by' => $request->user()->id,
            'avatar' => $request->hasFile('avatar')
                ? $request->file('avatar')->store('communities', 'public')
                : null,
            'banner' => $request->hasFile('banner')
                ? $request->file('banner')->store('communities/banners', 'public')
                : null,
        ]);

        // Auto-join creator as moderator
        $community->members()->attach($request->user()->id, ['role' => 'moderator']);
        $community->increment('members_count');

        return redirect()->route('communities.show', $community)->with('success', 'Community created!');
    }

    public function join(Request $request, Community $community)
    {
        $user = $request->user();

        if ($user->isMemberOf($community)) {
            return back()->with('info', 'You are already a member.');
        }

        $community->members()->attach($user->id, ['role' => 'member']);
        $community->increment('members_count');

        $this->gamification->awardPoints($user, 'community_joined', $community);
        $this->gamification->recordActivity($user, 'community_joined', $community);

        return back()->with('success', 'Welcome to the community!');
    }

    public function leave(Request $request, Community $community)
    {
        $user = $request->user();

        if (! $user->isMemberOf($community)) {
            return back()->with('info', 'You are not a member.');
        }

        $community->members()->detach($user->id);
        $community->decrement('members_count');

        return back()->with('success', 'You have left the community.');
    }
}
