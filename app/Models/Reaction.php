<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Reaction extends Model
{
    use HasFactory;

    public const DEFAULT_EMOJI = '❤️';

    public const EMOJIS = [
        '❤️' => 'Love',
        '😂' => 'Funny',
        '😡' => 'Angry',
        '🔥' => 'Fire',
        '👏' => 'Clap',
        '😭' => 'Cry',
    ];

    protected $fillable = [
        'user_id',
        'target_type',
        'target_id',
        'emoji',
    ];

    protected $casts = [
        'target_id' => 'string',
    ];

    public static function targetTypeFor(mixed $target): string
    {
        if (is_string($target)) {
            return $target;
        }

        return match (true) {
            $target instanceof Post => 'post',
            $target instanceof Comment => 'comment',
            default => class_basename($target),
        };
    }

    public static function targetIdFor(mixed $target): string
    {
        return is_object($target) ? (string) $target->getKey() : (string) $target;
    }

    public static function emojiOptions(): array
    {
        return self::EMOJIS;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForTarget(Builder $query, string $targetType, string|int $targetId): Builder
    {
        return $query->where('target_type', $targetType)
            ->where('target_id', (string) $targetId);
    }
}
