<?php

namespace App\Http\Controllers;

use App\Actions\Posts\CreatePost;
use App\Concerns\ExtractsMentions;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Services\GamificationService;
use App\Services\NotificationService;
use App\Services\ReactionService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ExtractsMentions;

    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
        protected ReactionService $reactions,
    ) {
    }

    public function index(Request $request)
    {
        $posts = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->latest()
            ->paginate(20);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->load([
            'user',
            'community',
            'comments' => fn($q) => $q->with(['user', 'replies.user'])->where('is_approved', true)->latest(),
        ]);

        return view('posts.show', compact('post'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate(CreatePost::rules());

        $post = app(CreatePost::class)->execute($request->user(), $validated);

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $post->update(['body' => $validated['body']]);

        // Re-extract mentions
        $post->mentions()->delete();
        $this->extractAndSaveMentions($validated['body'], $post);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }

    public function like(Request $request, Post $post)
    {
        $summary = $this->reactions->toggle(
            $request->user(),
            'post',
            $post->id,
            $request->string('emoji', Reaction::DEFAULT_EMOJI)->toString(),
        );

        return back()->with('success', ($summary['currentReaction'] ?? null) === null ? 'Reaction removed.' : 'Reaction updated.');
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $this->extractAndSaveMentions($validated['body'], $comment);

        $post->increment('comments_count');

        $this->gamification->awardPoints($request->user(), 'comment_created', $comment);
        $this->gamification->recordActivity($request->user(), 'comment_created', $comment);
        $this->notifications->notifyComment($request->user(), $post);

        return back()->with('success', 'Comment added!');
    }

    public function share(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        $post->shares()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'] ?? null,
        ]);

        $post->increment('shares_count');

        $this->gamification->awardPoints($request->user(), 'share_created', $post);

        return back()->with('success', 'Post shared!');
    }

    public function pin(Request $request, Post $post)
    {
        $this->authorize('pin', $post);

        $post->update(['is_pinned' => !$post->is_pinned]);

        $status = $post->is_pinned ? 'pinned' : 'unpinned';

        return back()->with('success', "Post {$status} successfully.");
    }

    public function deleteComment(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->post->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
