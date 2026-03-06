<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'community_id',
        'body',
        'type',
        'media',
        'is_pinned',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    // ── Accessors ──────────────────────────────────────────

    /**
     * Transform raw media path strings into structured PostMedia objects
     * with full URLs for the mobile app.
     */
    protected function media(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $paths = is_string($value) ? json_decode($value, true) : $value;
                if (!$paths || !is_array($paths)) {
                    return null;
                }

                return collect($paths)->map(function ($path, $index) {
                    // If already transformed (e.g. from a nested call), return as-is
                    if (is_array($path) && isset($path['url'])) {
                        return $path;
                    }

                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                    return [
                        'id' => $index + 1,
                        'url' => asset('storage/' . $path),
                        'type' => in_array($ext, ['mp4', 'webm', 'mov']) ? 'video' : 'image',
                        'thumbnail' => null,
                    ];
                })->all();
            },
            set: fn($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function mentions(): MorphMany
    {
        return $this->morphMany(Mention::class, 'mentionable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeTrending($query)
    {
        return $query->approved()
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('likes_count')
            ->orderByDesc('comments_count');
    }

    public function scopeFeed($query, User $user)
    {
        $followingQuery = $user->following()->select('users.id');

        return $query->approved()
            ->where(function ($q) use ($user, $followingQuery) {
                $q->whereIn('user_id', $followingQuery)
                    ->orWhere('user_id', $user->id);
            })
            ->latest();
    }
}
