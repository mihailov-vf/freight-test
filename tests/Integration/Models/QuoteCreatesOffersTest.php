<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Data\Quote\Dispatcher;
use App\Data\Quote\QuoteRequest;
use App\Data\Quote\Recipient;
use App\Data\Quote\Volume;
use App\Models\Quote;
use FreteRapido\Simulation\Simulation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Spatie\LaravelData\DataCollection;
use Tests\TestCase;

#[CoversClass(Quote::class)]
class QuoteCreatesOffersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestWith([1])]
    #[TestWith([5])]
    #[TestWith([0])]
    public function add_offers_from_simulation(int $offersNumber)
    {
        /** @var Simulation */
        $simulation = require dirname(__DIR__) . '/../data/simulation_return.php';
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
        $this->assertEquals($offersNumber, $quote->offers->count());
        $i = 0;
        foreach ($simulation->dispatchers as $quoteData) {
            foreach ($quoteData->offers as $offer) {
                $this->assertEquals($offer->carrier->name, $quote->offers->get($i)->name);
                $this->assertEquals($offer->service, $quote->offers->get($i)->service);
                $this->assertEquals($offer->deliveryTime->estimatedDate, $quote->offers->get($i)->estimated_date);
                $this->assertEquals($offer->expiration, $quote->offers->get($i)->expiration);
                $i++;
            }
        }
    }
}
