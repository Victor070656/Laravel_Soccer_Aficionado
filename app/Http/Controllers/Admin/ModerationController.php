<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function __construct(
        protected NotificationService $notifications,
    ) {
    }
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

        $this->notifications->send(
            $post->user,
            'moderation',
            'Your post has been approved',
            'Your post is now visible to the community.',
            $post
        );

        return back()->with('success', 'Post approved.');
    }

    public function rejectPost(Post $post)
    {
        $this->notifications->send(
            $post->user,
            'moderation',
            'Your post has been removed',
            'Your post was removed as it did not meet community guidelines.',
            $post
        );

        $post->delete();

        return back()->with('success', 'Post rejected and removed.');
    }

    public function pendingComments()
    {
        $comments = Comment::with(['user', 'post'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(25);

        return view('admin.moderation.pending-comments', compact('comments'));
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Comment approved.');
    }

    public function deleteComment(Comment $comment)
    {
        $comment->post->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
