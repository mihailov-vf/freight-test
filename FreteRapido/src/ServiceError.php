<?php

declare(strict_types=1);

namespace FreteRapido;

final class ServiceError extends Data
{
    public function __construct(
        public readonly int $serviceCode,
        public readonly string $reason,
        public readonly string $responseBody,
        public readonly int $suggestedCode,
        public readonly string $message,
    ) {
    }
}
