<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'slug',
        'photo',
        'position',
        'jersey_number',
        'nationality',
        'date_of_birth',
        'height_cm',
        'weight_kg',
        'preferred_foot',
        'bio',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function matchEvents(): HasMany
    {
        return $this->hasMany(MatchEvent::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(MatchEvent::class)->whereIn('type', ['goal', 'penalty']);
    }

    public function assists(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'secondary_player_id')->where('type', 'goal');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/'.$this->photo) : null;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }
}
