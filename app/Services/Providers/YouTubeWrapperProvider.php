<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Services\Contracts\ExternalAudioProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class YouTubeWrapperProvider implements ExternalAudioProviderInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.youtube_wrapper.base_url');
        $this->apiKey = config('services.youtube_wrapper.api_key');
    }
    public function fetchStreamDetails(string $externalId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept'        => 'application/json',
            ])
            ->timeout(10) 
            ->retry(2, 100) 
            ->get("{$this->baseUrl}/extract", [
                'video_id' => $externalId,
                'format'   => 'bestaudio', 
            ]);

            if ($response->failed()) {
                throw new RuntimeException("Falha ao comunicar com o provider de áudio. Status: {$response->status()}");
            }
            $data = $response->json();

            if (empty($data['stream_url'])) {
                throw new RuntimeException("Stream URL não disponível para a faixa: {$externalId}");
            }

            return [
                'title'            => $data['title'] ?? 'Unknown Title',
                'artist_name'      => $data['uploader'] ?? 'Unknown Artist', 
                'duration_seconds' => (int) ($data['duration'] ?? 0),
                'stream_url'       => $data['stream_url'], 
                'album_name'       => $data['album'] ?? null,
                'cover_url'        => $data['thumbnail'] ?? null,
            ];

        } catch (\Throwable $e) {
            Log::error('YouTubeWrapperProvider Error: ' . $e->getMessage(), [
                'external_id' => $externalId,
                'trace'       => $e->getTraceAsString(),
            ]);
            throw new RuntimeException("Não foi possível resolver o áudio para o ID fornecido.", 0, $e);
        }
    }
}
