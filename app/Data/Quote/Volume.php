<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

final class Volume extends Data
{
    public function __construct(
        #[Numeric]
        public readonly int $category,
        #[Numeric]
        public readonly int $amount,
        #[Numeric]
        #[MapName(SnakeCaseMapper::class)]
        public readonly float $unitaryWeight,
        #[Numeric]
        #[MapOutputName('unitary_price')]
        public readonly float $price,
        public readonly string $sku,
        #[Numeric]
        public readonly float $height,
        #[Numeric]
        public readonly float $width,
        #[Numeric]
        public readonly float $length,
    ) {
    }
}
