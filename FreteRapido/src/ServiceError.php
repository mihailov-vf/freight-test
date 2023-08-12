<?php

declare(strict_types=1);

namespace FreteRapido;

final class ServiceError extends Data
{
    public readonly string $message;

    public function __construct(
        public readonly int $code,
        public readonly string $reason,
        public readonly string $responseBody,
    ) {
        $this->extractErrorMessage();
    }

    private function extractErrorMessage(): void
    {
        $data = json_decode($this->responseBody, true);
        /** @phpstan-ignore-next-line */
        $this->message = $data['error'];
    }
}
