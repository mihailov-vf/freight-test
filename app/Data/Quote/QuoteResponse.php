<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class QuoteResponse extends Data
{
    /** @param CarrierOffer[]|DataCollection<string,CarrierOffer> $offers */
    public function __construct(
        #[DataCollectionOf(CarrierOffer::class)]
        #[MapOutputName('carrier')]
        public readonly DataCollection $offers
    ) {
    }
}
