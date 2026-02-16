<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(User $user, string $type, string $title, ?string $message = null, $notifiable = null, ?array $data = null): Notification
    {
        return Notification::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'notifiable_type' => $notifiable ? get_class($notifiable) : null,
            'notifiable_id' => $notifiable?->id,
            'data' => $data,
        ]);
    }

    /**
     * Notify on a new like.
     */
    public function notifyLike(User $liker, $likeable): void
    {
        $owner = $likeable->user;
        if ($owner->id === $liker->id) {
            return;
        }

        $type = class_basename($likeable);
        $this->send(
            $owner,
            'like',
            "{$liker->name} liked your {$type}",
            null,
            $likeable,
            ['liker_id' => $liker->id]
        );
    }

    /**
     * Notify on a new comment.
     */
    public function notifyComment(User $commenter, $post): void
    {
        if ($post->user_id === $commenter->id) {
            return;
        }

        $this->send(
            $post->user,
            'comment',
            "{$commenter->name} commented on your post",
            null,
            $post,
            ['commenter_id' => $commenter->id]
        );
    }

    /**
     * Notify on a new follower.
     */
    public function notifyFollow(User $follower, User $followed): void
    {
        $this->send(
            $followed,
            'follow',
            "{$follower->name} started following you",
            null,
            $follower,
            ['follower_id' => $follower->id]
        );
    }

    /**
     * Notify community members.
     */
    public function notifyCommunity($community, string $title, ?string $message = null): void
    {
        $community->members->each(function ($member) use ($community, $title, $message) {
            $this->send($member, 'community', $title, $message, $community);
        });
    }

    /**
     * Get unread notification count.
     */
    public function unreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)->unread()->count();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);
    }
}
