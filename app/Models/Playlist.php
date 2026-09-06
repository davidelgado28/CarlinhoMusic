<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'title',
        'artist_name',
        'album_name',
        'cover_url',
        'duration_seconds',
        'play_count',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'play_count' => 'integer',
    ];

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_track')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function listeningHistories(): HasMany
    {
        return $this->hasMany(ListeningHistory::class);
    }
}
