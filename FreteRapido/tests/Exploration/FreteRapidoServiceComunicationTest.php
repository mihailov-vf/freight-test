<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Exploration;

use DateTime;
use FreteRapido\Simulation\ShippingInfo;
use FreteRapido\Simulation\Simulation;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FreteRapidoServiceComunicationTest extends TestCase
{
    #[Test]
    public function quote_simulate()
    {
        try {
            $credenciais = ShippingInfo::from(require __DIR__ . '/quote_simulate.php');
        } catch (\Illuminate\Validation\ValidationException $th) {
            var_dump($th->errors());
            $this->fail();
        }

        $response = Http::send('POST', 'https://sp.freterapido.com/api/v3/quote/simulate', [
            'body' => $credenciais->toJson()
        ]);

        $this->assertTrue($response->successful());
        try {
            echo $response->body();
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
}
