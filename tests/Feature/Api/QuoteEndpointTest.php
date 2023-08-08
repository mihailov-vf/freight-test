<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuoteEndpointTest extends TestCase
{
    const URI = '/api/quote';

    private function validVolumes()
    {
        return [
            [
                "category" => 7,
                "amount" => 1,
                "unitary_weight" => 5,
                "price" => 349,
                "sku" => "abc-teste-123",
                "height" => 0.2,
                "width" => 0.2,
                "length" => 0.2
            ],
            [
                "category" => 7,
                "amount" => 2,
                "unitary_weight" => 4,
                "price" => 556,
                "sku" => "abc-teste-527",
                "height" => 0.4,
                "width" => 0.6,
                "length" => 0.15
            ]
        ];
    }

    #[Test]
    public function returns_carriers_prices_list_on_freight_quote()
    {
        $response = $this->postJson(self::URI, [
            "recipient" => [
                "address" => [
                    "zipcode" => "01311000"
                ]
            ],
            "volumes" => $this->validVolumes()
        ]);

        $response->assertOk();
        $response->assertJsonIsArray('carrier');
        $response->assertJsonStructure([
            'carrier' => [
                [
                    'name',
                    'service',
                    'deadline',
                    'price'
                ]
            ]
        ]);
    }

    #[Test]
    public function returns_validation_message_on_invalid_request()
    {
        $this->markTestIncomplete();
    }

    #[Test]
    public function returns_readable_message_on_internal_error()
    {
        $this->markTestIncomplete();
    }

    #[Test]
    public function returns_readable_message_on_external_service_failure()
    {
        $this->markTestIncomplete();
    }

    #[Test]
    public function requires_token_authentication()
    {
        $this->markTestIncomplete();
    }
}
