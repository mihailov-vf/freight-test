<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Quote\Dispatcher;
use App\Data\Quote\QuoteRequest;
use App\Data\Quote\Recipient;
use App\Data\Quote\Volume;
use App\Models\Quote;
use FreteRapido\Simulation\Simulation;
use Spatie\LaravelData\DataCollection;

trait PrepareQuote
{
    protected function prepareQuote(int $offersNumber)
    {
        /** @var Simulation */
        $simulation = require __DIR__ . '/data/simulation_return.php';
        /** @var DataCollection */
        $volumes = Volume::collection([]);
        $quoteRequest = new QuoteRequest(
            Recipient::from(['address' => ['zipcode' => fake()->postcode()]]),
            $volumes
        );
        $quoteRequest->additional([
            'dispatcher' => Dispatcher::from([
                'registered_number' => fake()->cnpj(),
                'address' => ['zipcode' => fake()->postcode()],
            ])
        ]);
        /** @var Quote */
        $quote = Quote::create($quoteRequest->toArray());
        $quote->save();

        $quote->addOffersFrom(Simulation::from($simulation));
        $quote->save();

        return [
            $quote,
            $simulation
        ];
    }
}
