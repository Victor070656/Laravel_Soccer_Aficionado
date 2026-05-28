<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReactionService
{
    public function toggle(User $user, string $targetType, string|int $targetId, string $emoji): array
    {
        return DB::transaction(function () use ($user, $targetType, $targetId, $emoji) {
            $existing = Reaction::query()
                ->where('user_id', $user->id)
                ->forTarget($targetType, $targetId)
                ->lockForUpdate()
                ->first();

            if ($existing && $existing->emoji === $emoji) {
                $existing->delete();
                $this->adjustTargetCount($targetType, $targetId, -1);

                return $this->summary($targetType, $targetId, $user);
            }

            if ($existing) {
                $existing->update(['emoji' => $emoji]);

                return $this->summary($targetType, $targetId, $user);
            }

            Reaction::create([
                'user_id' => $user->id,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'emoji' => $emoji,
            ]);

            $this->adjustTargetCount($targetType, $targetId, 1);

            return $this->summary($targetType, $targetId, $user);
        });
    }

    public function summary(string $targetType, string|int $targetId, ?User $user = null): array
    {
        $query = Reaction::query()->forTarget($targetType, $targetId);

        $counts = $query
            ->select('emoji', DB::raw('COUNT(*) as total'))
            ->groupBy('emoji')
            ->pluck('total', 'emoji')
            ->all();

        $currentReaction = $user
            ? Reaction::query()
                ->where('user_id', $user->id)
                ->forTarget($targetType, $targetId)
                ->value('emoji')
            : null;

        $buttons = collect(Reaction::emojiOptions())
            ->map(function (string $label, string $emoji) use ($counts, $currentReaction) {
                return [
                    'emoji' => $emoji,
                    'label' => $label,
                    'count' => (int) ($counts[$emoji] ?? 0),
                    'active' => $currentReaction === $emoji,
                ];
            })
            ->values()
            ->all();

        return [
            'buttons' => $buttons,
            'currentReaction' => $currentReaction,
            'totalCount' => array_sum(array_map('intval', $counts)),
        ];
    }

    private function adjustTargetCount(string $targetType, string|int $targetId, int $delta): void
    {
        match ($targetType) {
            'post' => Post::query()->whereKey($targetId)->increment('likes_count', $delta),
            'comment' => Comment::query()->whereKey($targetId)->increment('likes_count', $delta),
            default => null,
        };
    }
}
