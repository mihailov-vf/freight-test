<?php

declare(strict_types=1);

namespace FreteRapido\Data;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Casts\Uncastable;

class DateTimeConstructorParse implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): DateTimeInterface|Uncastable
    {
        try {
            return new DateTimeImmutable($value) ?: Uncastable::create();
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format', previous: $e);
        }
    }
}
