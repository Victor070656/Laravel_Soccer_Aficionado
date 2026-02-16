<?php

namespace App\Http\Controllers\Api;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Community::where('is_active', true)->withCount('members');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $communities = $query->orderByDesc('members_count')->paginate(20);

        return $this->success($communities);
    }

    public function show(Community $community)
    {
        $community->load(['club', 'creator']);
        $community->loadCount('members');

        return $this->success($community);
    }

    public function join(Request $request, Community $community)
    {
        $user = $request->user();

        if ($user->isMemberOf($community)) {
            return $this->error('Already a member.', 422);
        }

        $community->members()->attach($user->id, ['role' => 'member']);
        $community->increment('members_count');

        return $this->success(null, 'Joined community.');
    }

    public function leave(Request $request, Community $community)
    {
        $user = $request->user();

        if (!$user->isMemberOf($community)) {
            return $this->error('Not a member.', 422);
        }

        $community->members()->detach($user->id);
        $community->decrement('members_count');

        return $this->success(null, 'Left community.');
    }
}
