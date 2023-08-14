<?php

declare(strict_types=1);

namespace App\Data\Quote;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class QuoteRequest extends Data
{
    /**
     * @param Volume[]|DataCollection<string,Volume> $volumes
     */
    public function __construct(
        public readonly Recipient $recipient,
        #[DataCollectionOf(Volume::class)]
        public readonly DataCollection $volumes,
    ) {
    }
}
