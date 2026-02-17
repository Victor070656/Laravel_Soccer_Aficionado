<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Club;
use Illuminate\Http\Request;

class CommunityManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::with(['club', 'creator'])->withCount('members');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->has('inactive')) {
            $query->where('is_active', false);
        }

        $communities = $query->orderByDesc('members_count')->paginate(25);

        return view('admin.communities.index', compact('communities'));
    }

    public function toggleActive(Community $community)
    {
        $community->update(['is_active' => !$community->is_active]);

        $status = $community->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Community {$status}.");
    }

    public function destroy(Community $community)
    {
        $community->members()->detach();
        $community->posts()->delete();
        $community->delete();

        return redirect()->route('admin.communities.index')->with('success', 'Community deleted.');
    }
}
