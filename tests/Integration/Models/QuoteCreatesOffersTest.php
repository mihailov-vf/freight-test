<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\PrepareQuote;
use Tests\TestCase;

#[CoversClass(Quote::class)]
class QuoteCreatesOffersTest extends TestCase
{
    use RefreshDatabase;
    use PrepareQuote;

    #[Test]
    #[TestWith([1])]
    #[TestWith([5])]
    #[TestWith([0])]
    public function add_offers_from_simulation(int $offersNumber)
    {
        [$quote, $simulation] = $this->prepareQuote($offersNumber);
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
