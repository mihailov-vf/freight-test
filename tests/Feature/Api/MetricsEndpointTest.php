<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MetricsEndpointTest extends TestCase
{
    const URI = '/api/metrics';

    #[Test]
    public function returns_all_metrics()
    {
        $response = $this->get(self::URI);

        $response->assertOk();
        $response->assertJsonStructure([
            'quotes',
            'carriers_metrics' => [
                [
                    'name',
                    'result_number',
                    'total_price',
                    'average_price',
                ]
            ],
            'lower_price',
            'highest_price',
        ]);
    }

    #[Test]
    public function returns_metrics_from_last_quotes()
    {
        $quotesNumber = 5;
        $uri = self::URI . "?last_quotes={$quotesNumber}";
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertJsonStructure([
            'carriers_metrics' => [
                [
                    'name',
                    'result_number',
                    'total_price',
                    'average_price',
                ]
            ],
            'lower_price',
            'highest_price',
        ]);
        $this->assertNotEmpty($response->carriers_metrics);
    }
}
