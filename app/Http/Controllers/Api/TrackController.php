<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\RecordListeningProgressJob;
use App\Services\TrackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackController extends Controller
{
    public function __construct(
        private readonly TrackService $trackService
    ) {}

    public function stream(string $externalId): JsonResponse
    {
        $trackData = $this->trackService->getTrackStreamData($externalId);
        return response()->json(['data' => $trackData], Response::HTTP_OK);
    }

    public function reportProgress(Request $request, int $trackId): JsonResponse
    {
        $validated = $request->validate([
            'position_seconds' => 'required|integer|min:0'
        ]);

        RecordListeningProgressJob::dispatch(
            $request->user()->id,
            $trackId,
            $validated['position_seconds']
        )->onQueue('telemetry');

        return response()->json(['status' => 'queued'], Response::HTTP_ACCEPTED);
    }
}
