<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface ExternalAudioProviderInterface
{
    public function fetchStreamDetails(string $externalId): array;
}
