<?php

declare(strict_types=1);

namespace App\Data\Metrics;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class CarrierMetrics extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly int $offersQuantity,
        public readonly float $totalPrice,
        public readonly float $averagePrice,
    ) {
    }
}
