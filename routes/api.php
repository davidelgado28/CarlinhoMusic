<?php

use App\Http\Controllers\Api\TrackController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/tracks/stream/{externalId}', [TrackController::class, 'stream']);
    Route::post('/tracks/{trackId}/progress', [TrackController::class, 'reportProgress']);
});
