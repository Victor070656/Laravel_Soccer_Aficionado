<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_team_id',
        'name',
        'slug',
        'short_name',
        'logo',
        'cover_image',
        'description',
        'country',
        'city',
        'stadium',
        'founded_year',
        'website',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'founded_year' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────────

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class)->withPivot('season')->withTimestamps();
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(FootballMatch::class, 'home_club_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(FootballMatch::class, 'away_club_id');
    }

    public function fans(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('is_primary')->withTimestamps();
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Find or create a local Club record from normalised API team data.
     */
    public static function fromApiTeam(array $team): self
    {
        return static::updateOrCreate(
            ['api_team_id' => $team['id']],
            [
                'name' => $team['name'],
                'slug' => \Illuminate\Support\Str::slug($team['name']),
                'logo' => $team['logo'] ?? null,
                'country' => $team['country'] ?? null,
                'city' => $team['venue']['city'] ?? null,
                'stadium' => $team['venue']['name'] ?? null,
                'founded_year' => $team['founded'] ?? null,
                'is_active' => true,
            ],
        );
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // API logos are already full URLs
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }

        return asset('storage/' . $this->logo);
    }

    public function getAllMatches()
    {
        return FootballMatch::where('home_club_id', $this->id)
            ->orWhere('away_club_id', $this->id)
            ->orderByDesc('kick_off');
    }
}
