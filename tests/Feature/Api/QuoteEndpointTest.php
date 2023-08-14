<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\QuoteController;
use App\Models\Quote;
use FreteRapido\FreteRapido;
use FreteRapido\ServiceError;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[CoversClass(QuoteController::class)]
class QuoteEndpointTest extends TestCase
{
    use RefreshDatabase;

    public const URI = '/api/quote';

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
        $this->seed();

        $offersNumber = 5;
        $this->instance(FreteRapido::class, Mockery::mock(FreteRapido::class, function (MockInterface $mock) use ($offersNumber) {
            $mock->shouldReceive('simulate')->once()->andReturn(require dirname(__DIR__) . '/../data/simulation_return.php');
        }));

        $response = $this->postJson(self::URI, [
            "recipient" => [
                "address" => [
                    "zipcode" => fake()->postcode()
                ]
            ],
            "volumes" => $this->validVolumes()
        ]);

        $this->assertDatabaseCount('quotes', 1);
        $this->assertDatabaseHas('carrier_offers', [
            'quote_id' => Quote::first()->id
        ]);
        $this->assertDatabaseCount('carrier_offers', $offersNumber);

        $response->assertCreated();
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
        $response = $this->postJson(self::URI, [
            "recipient" => [
                "address" => []
            ],
            "volumes" => $this->validVolumes()
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure(['message', 'errors']);
    }

    #[Test]
    public function returns_readable_message_on_internal_error()
    {
        // The lack of dispatchers table records will throw an error
        $response = $this->postJson(self::URI, [
            "recipient" => [
                "address" => [
                    "zipcode" => "01311-000"
                ]
            ],
            "volumes" => $this->validVolumes()
        ]);

        $response->assertServerError();
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Verifique as configurações do serviço');
    }

    #[Test]
    #[TestWith([
        400,
        'Bad Request',
        '{"details":[],"error":"json: cannot unmarshal string into Go struct field SimulationRecipient.recipient.zipcode of type uint32"}',
        400,
        'Houve um erro ao enviar a requisição ao serviço externo. Por favor, verifique os dados enviados e tente novamente.'
    ])]
    #[TestWith([
        500,
        'Internal Server Error',
        '{"details":[],"error":"Not Implemented"}',
        500,
        'Houve um erro com o serviço externo. Por favor, após alguns instantes tente novamente.'
    ])]
    public function returns_readable_message_on_external_service_failure(
        int $errorCode,
        string $errorReason,
        string $errorBody,
        int $expectedCode,
        string $expectedMessage
    ) {
        $this->seed();

        $serviceError = new ServiceError($errorCode, $errorReason, $errorBody, $expectedCode, $expectedMessage);
        $this->instance(FreteRapido::class, Mockery::mock(
            FreteRapido::class,
            function (MockInterface $mock) use ($serviceError) {
                $mock->shouldReceive('simulate')->once()->andReturn($serviceError);
            }
        ));

        $response = $this->postJson(self::URI, [
            "recipient" => [
                "address" => [
                    "zipcode" => "01311-000"
                ]
            ],
            "volumes" => $this->validVolumes()
        ]);

        $response->assertStatus($expectedCode);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', $expectedMessage);
    }

    #[Test]
    public function requires_token_authentication()
    {
        $this->markTestIncomplete();
    }
}
