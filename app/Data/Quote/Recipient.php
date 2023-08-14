<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Data;

final class Recipient extends Data
{
    public function __construct(
        public readonly Address $address
    ) {
    }
}
