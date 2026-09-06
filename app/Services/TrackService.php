<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Track;
use App\Services\Contracts\ExternalAudioProviderInterface;
use Illuminate\Support\Facades\Cache;

class TrackService
{
    private const CACHE_TTL_SECONDS = 86400; 

    public function __construct(
        private readonly ExternalAudioProviderInterface $audioProvider
    ) {}

    public function getTrackStreamData(string $externalId): array
    {
        $cacheKey = "track:stream:{$externalId}";

        return Cache::store('redis')->remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($externalId) {
            $externalData = $this->audioProvider->fetchStreamDetails($externalId);

            $track = Track::updateOrCreate(
                ['external_id' => $externalId],
                [
                    'title' => $externalData['title'],
                    'artist_name' => $externalData['artist_name'],
                    'album_name' => $externalData['album_name'] ?? null,
                    'cover_url' => $externalData['cover_url'] ?? null,
                    'duration_seconds' => $externalData['duration_seconds'],
                ]
            );

            return [
                'id' => $track->id,
                'external_id' => $track->external_id,
                'title' => $track->title,
                'artist_name' => $track->artist_name,
                'stream_url' => $externalData['stream_url'],
                'duration_seconds' => $track->duration_seconds,
            ];
        });
    }
}
