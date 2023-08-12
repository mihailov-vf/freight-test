<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Unit;

use FreteRapido\{
    Credentials,
    FreteRapido,
    FreteRapidoApiVersion
};
use FreteRapido\Simulation\{
    Shipper,
    ShippingInfo,
    Simulation
};
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileObject;
use Tests\TestCase;

#[CoversClass(FreteRapido::class)]
class FreteRapidoTest extends TestCase
{
    private Credentials $credentials;
    private MockObject|Client $httpClient;
    private FreteRapido $freteRapido;
    private ShippingInfo $validShipping;

    protected function setUp(): void
    {
        parent::setUp();

        $cnpj = fake()->cnpj();
        $token = str_replace('-', '', fake()->uuid());
        $codigoPlataforma = fake()->word();

        $this->credentials = new Credentials($cnpj, $token, $codigoPlataforma);

        $this->httpClient = $this->createMock(Client::class);
        $this->freteRapido = new FreteRapido(
            $this->credentials,
            $this->httpClient
        );

        $cep = fake()->postcode();
        $recipientCep = fake()->postcode();
        $this->validShipping = ShippingInfo::from(require __DIR__ . '/../data/shipping_info.php');
    }

    #[Test]
    public function simulate_with_success(): void
    {
        $json = new SplFileObject(dirname(__DIR__) . '/data/simulation_real_response.json');
        $simulationResponse = $json->fread($json->getSize());

        $expectedShippingInfo = clone $this->validShipping;
        $expectedShippingInfo->shipper = Shipper::from([
            'registered_number' => $this->credentials->cnpj,
            'token' => $this->credentials->token,
            'platform_code' => $this->credentials->codigoPlataforma
        ]);
        $expectedResponse = new Response(body: Simulation::from($simulationResponse)->toJson());
        $this->httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) use ($expectedShippingInfo) {
                $expectedUri = FreteRapidoApiVersion::V3->value . '/quote/simulate';
                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals($expectedUri, (string)$request->getUri());
                $this->assertJsonStringEqualsJsonString(
                    $expectedShippingInfo->toJson(),
                    $request->getBody()->getContents()
                );

                $request->getBody()->rewind();
                return true;
            }))
            ->willReturn($expectedResponse);

        $simulation = $this->freteRapido->simulate($this->validShipping);

        // Asserts that shipper info was filled
        $this->assertInstanceOf(Shipper::class, $this->validShipping->shipper);
        $this->assertInstanceOf(Simulation::class, $simulation);
    }
}
