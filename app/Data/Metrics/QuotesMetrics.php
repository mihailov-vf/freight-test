<?php

declare(strict_types=1);

namespace App\Data\Metrics;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class QuotesMetrics extends Data
{
    /**
     * @param CarrierMetrics[]|DataCollection<string,CarrierMetrics> $carriersMetrics
     */
    public function __construct(
        #[DataCollectionOf(CarrierMetrics::class)]
        public readonly DataCollection $carriersMetrics,
        public readonly float $lowerPrice,
        public readonly float $higherPrice,
    ) {
    }

    public function defaultWrap(): string
    {
        return 'quotes_metrics';
    }
}
