<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'competition_id',
        'home_club_id',
        'away_club_id',
        'season',
        'matchday',
        'round',
        'venue',
        'kick_off',
        'status',
        'home_score',
        'away_score',
        'home_score_ht',
        'away_score_ht',
        'attendance',
        'referee',
    ];

    protected function casts(): array
    {
        return [
            'kick_off' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function homeClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function awayClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute');
    }

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class, 'match_id');
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')->where('kick_off', '>', now())->orderBy('kick_off');
    }

    public function scopeLive($query)
    {
        return $query->whereIn('status', ['live', 'half_time']);
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 'finished')->orderByDesc('kick_off');
    }

    public function scopeForClub($query, int $clubId)
    {
        return $query->where(function ($q) use ($clubId) {
            $q->where('home_club_id', $clubId)->orWhere('away_club_id', $clubId);
        });
    }

    // ── Helpers ────────────────────────────────────────────

    public function isLive(): bool
    {
        return in_array($this->status, ['live', 'half_time']);
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function getScoreDisplayAttribute(): string
    {
        if ($this->isFinished() || $this->isLive()) {
            return "{$this->home_score} - {$this->away_score}";
        }

        return 'vs';
    }

    public function getWinner(): ?Club
    {
        if (!$this->isFinished() || $this->home_score === $this->away_score) {
            return null;
        }

        return $this->home_score > $this->away_score ? $this->homeClub : $this->awayClub;
    }
}
