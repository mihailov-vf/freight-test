<?php

declare(strict_types=1);

namespace App\Data\Quote;

use FreteRapido\Data\RemoveNumberFormat;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class Address extends Data
{
    public function __construct(
        #[Regex('/\d{5}-?\d{3}/')]
        #[WithCast(RemoveNumberFormat::class, forceType: false)]
        public readonly string $zipcode
    ) {
    }
}
