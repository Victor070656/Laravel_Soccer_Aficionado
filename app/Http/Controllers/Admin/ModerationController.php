<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'reportable', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(25);

        return view('admin.moderation.reports', compact('reports'));
    }

    public function reviewReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report updated.');
    }

    public function pendingPosts()
    {
        $posts = Post::with(['user', 'community'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(25);

        return view('admin.moderation.pending-posts', compact('posts'));
    }

    public function approvePost(Post $post)
    {
        $post->update(['is_approved' => true]);

        return back()->with('success', 'Post approved.');
    }

    public function rejectPost(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Post rejected and deleted.');
    }
}
