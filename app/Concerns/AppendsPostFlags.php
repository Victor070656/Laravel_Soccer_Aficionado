<?php

namespace App\Concerns;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait AppendsPostFlags
{
    /**
     * Append is_liked and is_owner flags to posts.
     *
     * @param  LengthAwarePaginator|Collection|Post|array  $posts
     */
    protected function appendPostFlags(mixed $posts, ?User $user = null): mixed
    {
        $user ??= auth('sanctum')->user();

        $addFlags = function (Post $post) use ($user) {
            $post->reaction = $user ? $user->reactionFor($post) : null;
            $post->is_liked = $post->reaction !== null;
            $post->is_owner = $user ? $post->user_id === $user->id : false;

            return $post;
        };

        if ($posts instanceof LengthAwarePaginator) {
            $posts->getCollection()->transform($addFlags);

            return $posts;
        }

        if ($posts instanceof Collection) {
            return $posts->transform($addFlags);
        }

        if ($posts instanceof Post) {
            return $addFlags($posts);
        }

        return $posts;
    }
}
