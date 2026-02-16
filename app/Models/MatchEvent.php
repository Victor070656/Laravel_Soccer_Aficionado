<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'player_id',
        'club_id',
        'type',
        'minute',
        'extra_minute',
        'description',
        'secondary_player_id',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function secondaryPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'secondary_player_id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function getTimeDisplayAttribute(): string
    {
        return $this->extra_minute
            ? "{$this->minute}+{$this->extra_minute}'"
            : "{$this->minute}'";
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'goal' => '⚽',
            'own_goal' => '⚽ (OG)',
            'penalty' => '⚽ (P)',
            'yellow_card' => '🟨',
            'red_card' => '🟥',
            'substitution' => '🔄',
            'var' => '📺',
            default => '•',
        };
    }
}
