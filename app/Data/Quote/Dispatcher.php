<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

final class Dispatcher extends Data
{
    public function __construct(
        #[MapName(SnakeCaseMapper::class)]
        public readonly string $registeredNumber,
        public readonly Address $address,
    ) {
    }
}
