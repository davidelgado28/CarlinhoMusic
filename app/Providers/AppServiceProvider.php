<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Contracts\ExternalAudioProviderInterface;
use App\Services\Providers\YouTubeWrapperProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ExternalAudioProviderInterface::class,
            YouTubeWrapperProvider::class
        );
    }
    public function boot(): void
    {
    }
}
