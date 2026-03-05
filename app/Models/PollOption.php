<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    protected $fillable = ['poll_id', 'player_id', 'label', 'image', 'votes_count'];

    protected $appends = ['percentage'];

    /**
     * Hide the inverse poll relation to prevent circular serialisation
     * (each option was embedding the full poll + all options again).
     */
    protected $hidden = ['poll'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function getPercentageAttribute(): float
    {
        $total = $this->poll->total_votes ?? 0;

        return $total > 0 ? round(($this->votes_count / $total) * 100, 1) : 0;
    }
}
