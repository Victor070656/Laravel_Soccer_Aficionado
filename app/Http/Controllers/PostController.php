<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Share;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {
    }

    public function index(Request $request)
    {
        $posts = Post::with(['user', 'community'])
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

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
            'community_id' => 'nullable|exists:communities,id',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:10240',
        ]);

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('posts', 'public');
            }
        }

        $post = $request->user()->posts()->create([
            'body' => $validated['body'],
            'community_id' => $validated['community_id'] ?? null,
            'type' => !empty($mediaPaths) ? 'image' : 'text',
            'media' => !empty($mediaPaths) ? $mediaPaths : null,
        ]);

        $this->gamification->awardPoints($request->user(), 'post_created', $post);
        $this->gamification->recordActivity($request->user(), 'post_created', $post);

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully!');
    }

    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }

    public function like(Request $request, Post $post)
    {
        $user = $request->user();

        if ($user->hasLiked($post)) {
            $post->likes()->where('user_id', $user->id)->delete();
            $post->decrement('likes_count');

            return back()->with('success', 'Like removed.');
        }

        $post->likes()->create(['user_id' => $user->id]);
        $post->increment('likes_count');

        $this->gamification->awardPoints($post->user, 'like_received', $post);
        $this->notifications->notifyLike($user, $post);

        return back()->with('success', 'Post liked!');
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
}
