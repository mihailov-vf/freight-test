<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\MetricsReader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\PrepareQuote;
use Tests\TestCase;

#[CoversClass(MetricsReader::class)]
class MetricsReaderTest extends TestCase
{
    use RefreshDatabase;
    use PrepareQuote;

    #[Test]
    public function map_metrics_data()
    {
        [$quote,] = $this->prepareQuote(20);

        $reader = new MetricsReader();
        $metrics = $reader->readMetricsFromLastQuotes(0);

        $this->assertEquals($quote->offers->min('price'), $metrics->lowerPrice);
        $this->assertEquals($quote->offers->max('price'), $metrics->higherPrice);

        $carriers = $quote->offers->sortBy(['quote_id', 'name'])->groupBy('name');
        foreach ($carriers->values() as $n => $carrier) {
            $this->assertEquals($carrier->count(), $metrics->carriersMetrics[$n]->offersQuantity);

            $this->assertEquals($carrier->first()->name, $metrics->carriersMetrics[$n]->name, 'TODO: Check other possibilities to sort fields with more precision');
            $this->assertEquals($carrier->sum('price'), $metrics->carriersMetrics[$n]->totalPrice);
            $this->assertEquals($carrier->avg('price'), $metrics->carriersMetrics[$n]->averagePrice);
        }
    }
}
