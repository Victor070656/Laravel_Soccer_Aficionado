<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'title',
        'description',
        'type',
        'closes_at',
        'is_active',
        'total_votes',
    ];

    protected function casts(): array
    {
        return [
            'closes_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected $appends = ['is_closed'];

    public function getIsClosedAttribute(): bool
    {
        return ! $this->isOpen();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function isOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->closes_at && $this->closes_at->isPast()) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closes_at')
                    ->orWhere('closes_at', '>', now());
            });
    }
}
