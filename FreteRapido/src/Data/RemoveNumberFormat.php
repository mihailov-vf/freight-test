<?php

declare(strict_types=1);

namespace FreteRapido\Data;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Stringable;

class RemoveNumberFormat implements Cast
{
    public function __construct(private bool $forceType = true)
    {
    }

    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        if (is_int($value)) {
            return $value;
        }
        if (!is_string($value) && !$value instanceof Stringable) {
            $foundType = get_debug_type($value);
            throw new \InvalidArgumentException("Expected value of type 'string' to cast found {$foundType}.");
        }

        $value = preg_replace('/\D/', '', $value);
        return $this->forceType ? intval($value) : $value;
    }
}
