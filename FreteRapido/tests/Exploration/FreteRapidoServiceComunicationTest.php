<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Exploration;

use FreteRapido\Simulation\ShippingInfo;
use FreteRapido\Simulation\Simulation;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use SplFileObject;
use Tests\TestCase;

#[CoversNothing]
class FreteRapidoServiceComunicationTest extends TestCase
{
    #[Test]
    public function quote_simulate()
    {
        try {
            $shippingInfo = ShippingInfo::from(require __DIR__ . '/quote_simulate.php');
        } catch (\Illuminate\Validation\ValidationException $th) {
            var_dump($th->errors());
            $this->fail();
        }

        $response = Http::send('POST', 'https://sp.freterapido.com/api/v3/quote/simulate', [
            'body' => $shippingInfo->toJson()
        ]);

        $this->assertTrue($response->successful());
        try {
            $body = $response->body();
            echo $body;

            $jsonFile = new SplFileObject(__DIR__ . '/../data/simulation_real_response.json', 'w');
            $jsonFile->fwrite($body);

            $simulation = Simulation::from($response->json());
        } catch (\Illuminate\Validation\ValidationException $th) {
            var_dump($th->errors());
            $this->fail();
        } catch (\Throwable $th) {
            echo $th->getMessage();
            $this->fail();
        }
        $this->assertIsObject($simulation);
    }

    #[Test]
    public function endpoint_not_found(): void
    {
        $response = Http::send('GET', 'https://sp.freterapido.com/api/v3/quote/asd?token=' . env('CREDENCIAIS_TOKEN'));

        $body = $response->body();
        echo $body;

        $jsonFile = new SplFileObject(__DIR__ . '/../data/simulation_endpoint_not_found_response.json', 'w');
        $jsonFile->fwrite($body);

        $this->assertTrue($response->failed());
        // $this->assertTrue($response->notFound());
        $this->assertTrue($response->serverError(), "Retorno de status inesperado: {$response->status()}");
    }

    #[Test]
    public function invalid_credentials(): void
    {
        $shippingData = require __DIR__ . '/quote_simulate.php';
        $shippingData['shipper']['token'][0] = '9';
        try {
            $shippingInfo = ShippingInfo::from($shippingData);
        } catch (\Illuminate\Validation\ValidationException $th) {
            var_dump($th->errors());
            $this->fail();
        }

        $response = Http::send('POST', 'https://sp.freterapido.com/api/v3/quote/simulate', [
            'body' => $shippingInfo->toJson()
        ]);

        $body = $response->body();
        echo $body;

        $jsonFile = new SplFileObject(__DIR__ . '/../data/simulation_invalid_credentials_response.json', 'w');
        $jsonFile->fwrite($body);

        $this->assertTrue($response->failed());
        // $this->assertTrue($response->unauthorized());
        $this->assertTrue($response->badRequest(), "Retorno de status inesperado: {$response->status()}");
    }

    #[Test]
    public function invalid_data(): void
    {
        $shippingData = require __DIR__ . '/quote_simulate.php';
        unset($shippingData['dispatchers']['volumes']);
        $response = Http::send('POST', 'https://sp.freterapido.com/api/v3/quote/simulate', [
            'json' => $shippingData
        ]);

        $body = $response->body();
        echo $body;

        $jsonFile = new SplFileObject(__DIR__ . '/../data/simulation_invalid_data_response.json', 'w');
        $jsonFile->fwrite($body);

        $this->assertTrue($response->failed());
        // $this->assertTrue($response->unprocessableEntity());
        $this->assertTrue($response->badRequest(), "Retorno de status inesperado: {$response->status()}");
    }
}
