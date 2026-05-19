<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link_url',
        'placement',
        'is_active',
        'starts_at',
        'ends_at',
        'click_count',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Get the full URL to the ad image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/'.$this->image);
    }

    /**
     * Check if this ad should currently be shown.
     */
    public function isRunning(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope to only active, currently-running ads.
     */
    public function scopeRunning($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            });
    }

    /**
     * Scope by placement location.
     */
    public function scopeForPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }
}
