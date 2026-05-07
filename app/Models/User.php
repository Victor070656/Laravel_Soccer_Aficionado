<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'country',
        'state',
        'timezone',
        'favorite_player_id',
        'favorite_coach',
        'football_personality',
        'points',
        'is_banned',
        'banned_at',
        'ban_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $appends = ['avatar_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function favoriteClubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class)->withPivot('is_primary')->withTimestamps();
    }

    public function favoritePlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'favorite_player_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)->withPivot('role')->withTimestamps();
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class)->withPivot('earned_at')->withTimestamps();
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function reportsFiled(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // ── Helper Methods ─────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function hasLiked($likeable): bool
    {
        return $this->likes()
            ->where('likeable_type', get_class($likeable))
            ->where('likeable_id', $likeable->id)
            ->exists();
    }

    public function hasVotedOn(Poll $poll): bool
    {
        return $this->votes()->where('poll_id', $poll->id)->exists();
    }

    public function isMemberOf(Community $community): bool
    {
        return $this->communities()->where('community_id', $community->id)->exists();
    }

    public function awardPoints(int $points, string $action, $pointable): void
    {
        $this->pointTransactions()->create([
            'points' => $points,
            'action' => $action,
            'pointable_type' => get_class($pointable),
            'pointable_id' => $pointable->id,
        ]);

        $this->increment('points', $points);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
