<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'user_id',
        'emoji',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getAvailableEmojis(): array
    {
        return [
            '⚽' => 'Goal',
            '🔥' => 'Fire',
            '💚' => 'Love',
            '😂' => 'LOL',
            '😡' => 'Angry',
            '🎉' => 'Party',
            '👏' => 'Clap',
            '😱' => 'Shock',
        ];
    }
}
