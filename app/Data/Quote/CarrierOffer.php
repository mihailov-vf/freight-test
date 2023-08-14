<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Data;

final class CarrierOffer extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $service,
        public readonly int $deadline,
        public readonly float $price,
    ) {
    }
}
