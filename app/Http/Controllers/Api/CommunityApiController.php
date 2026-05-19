<?php

namespace App\Http\Controllers\Api;

use App\Concerns\AppendsPostFlags;
use App\Models\Community;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommunityApiController extends BaseApiController
{
    use AppendsPostFlags;

    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {}

    public function index(Request $request)
    {
        $query = Community::with('club')->where('is_active', true)->withCount(['members', 'posts']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $communities = $query->orderByDesc('members_count')->paginate(20);

        return $this->success($communities);
    }

    public function show(Community $community)
    {
        $community->load(['club', 'creator']);
        $community->loadCount(['members', 'posts']);

        $posts = $community->posts()
            ->with(['user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->latest()
            ->paginate(20);

        $isMember = auth('sanctum')->check() && auth('sanctum')->user()->isMemberOf($community);

        $isOwner = auth('sanctum')->check() && $community->created_by === auth('sanctum')->user()->id;
        $this->appendPostFlags($posts);

        return $this->success([
            'community' => $community,
            'posts' => $posts,
            'is_member' => $isMember,
            'is_owner' => $isOwner,
        ]);
    }

    public function join(Request $request, Community $community)
    {
        $user = $request->user();

        if ($user->isMemberOf($community)) {
            return $this->error('Already a member.', 422);
        }

        $community->members()->attach($user->id, ['role' => 'member']);
        $community->increment('members_count');

        $this->gamification->awardPoints($user, 'community_joined', $community);
        $this->gamification->recordActivity($user, 'community_joined', $community);

        return $this->success(null, 'Joined community.');
    }

    public function leave(Request $request, Community $community)
    {
        $user = $request->user();

        if (! $user->isMemberOf($community)) {
            return $this->error('Not a member.', 422);
        }

        $community->members()->detach($user->id);
        $community->decrement('members_count');

        return $this->success(null, 'Left community.');
    }

    public function store(Request $request)
    {
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
            'slug' => Str::slug($validated['name']),
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

        $community->load(['club', 'creator']);
        $community->loadCount(['members', 'posts']);

        return $this->success($community, 'Community created!', 201);
    }

    public function update(Request $request, Community $community)
    {
        if ($community->created_by !== $request->user()->id) {
            return $this->error('Unauthorized.', 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'rules' => 'nullable|string|max:5000',
            'club_id' => 'nullable|exists:clubs,id',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ]);

        $data = [
            'description' => $validated['description'] ?? $community->description,
            'rules' => $validated['rules'] ?? $community->rules,
            'club_id' => $validated['club_id'] ?? $community->club_id,
        ];

        if (isset($validated['name']) && $validated['name'] !== $community->name) {
            $data['name'] = $validated['name'];
            $data['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('avatar')) {
            if ($community->avatar) {
                Storage::disk('public')->delete($community->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('communities', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($community->banner) {
                Storage::disk('public')->delete($community->banner);
            }
            $data['banner'] = $request->file('banner')->store('communities/banners', 'public');
        }

        $community->update($data);

        $community->load(['club', 'creator']);
        $community->loadCount(['members', 'posts']);

        return $this->success($community, 'Community updated.');
    }
}
