<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Simulation extends Data
{
    /** @param Quote[]|DataCollection<string,Quote> $dispatchers*/
    public function __construct(
        #[DataCollectionOf(Quote::class)]
        public readonly DataCollection $dispatchers
    ) {
    }
}
