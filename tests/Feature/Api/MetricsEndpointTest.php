<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\MetricsController;
use App\Models\MetricsReader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\PrepareQuote;
use Tests\TestCase;

#[CoversClass(MetricsController::class)]
#[CoversClass(MetricsReader::class)]
class MetricsEndpointTest extends TestCase
{
    use RefreshDatabase;
    use PrepareQuote;

    public const URI = '/api/metrics';

    #[Test]
    public function returns_all_metrics()
    {
        $this->prepareQuote(3);

        $response = $this->get(self::URI);

        $response->assertOk();
        $response->assertJsonStructure([
            'quotes_metrics' => [
                'carriers_metrics' => [
                    [
                        'name',
                        'offers_quantity',
                        'total_price',
                        'average_price',
                    ]
                ],
                'lower_price',
                'higher_price',
            ]
        ]);

        $response->assertJsonIsArray('quotes_metrics.carriers_metrics');
        $response->assertJsonCount(3, 'quotes_metrics.carriers_metrics');
    }

    #[Test]
    public function returns_metrics_from_last_quotes()
    {
        [$quote, ] = $this->prepareQuote(4);
        [$quote2, ] = $this->prepareQuote(4);
        [$quote3, ] = $this->prepareQuote(4);

        $quotesNumber = 2;
        $uri = self::URI . "?last_quotes={$quotesNumber}";
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertJsonStructure([
            'quotes_metrics' => [
                'carriers_metrics' => [
                    [
                        'name',
                        'offers_quantity',
                        'total_price',
                        'average_price',
                    ]
                ],
                'lower_price',
                'higher_price',
            ]
        ]);
        $response->assertJsonIsArray('quotes_metrics.carriers_metrics');
    }

    #[Test]
    public function returns_metrics_no_offers()
    {
        $this->prepareQuote(0);
        $quotesNumber = 2;
        $uri = self::URI . "?last_quotes={$quotesNumber}";
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertJsonStructure([
            'quotes_metrics' => [
                'carriers_metrics' => [],
                'lower_price',
                'higher_price',
            ]
        ]);
        $response->assertJsonIsArray('quotes_metrics.carriers_metrics');
        $response->assertJsonCount(0, 'quotes_metrics.carriers_metrics');
    }
}
