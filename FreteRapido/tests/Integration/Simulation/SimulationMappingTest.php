<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Integration\Simulation;

use FreteRapido\Simulation\Simulation;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimulationMappingTest extends TestCase
{
    #[Test]
    public function creating_from_valid_json()
    {
        $id = fake()->uuid();
        $requestId = fake()->uuid();
        $cnpj = fake()->cnpj(false);
        $cep = fake()->postcode();
        $carrier = fake()->company();
        $cnpjCarrier = fake()->cnpj();
        $service = fake()->word();
        $deliveryDate = fake()->dateTime('next week');
        $expiration = fake()->dateTime('tomorow');

        try {
            $simulation = Simulation::from(
                require __DIR__ . '/simulation_valid_json.php'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            echo $e->validator->errors();
            $this->fail('ValidationErrors');
        }

        $this->assertEquals(1, $simulation->dispatchers->count());

        $quote = $simulation->dispatchers[0];
        $this->assertEquals($id, $quote->id);
        $this->assertEquals($requestId, $quote->requestId);
        $this->assertEquals($cnpj, $quote->registeredNumberDispatcher);
        $this->assertEquals($cnpj, $quote->registeredNumberShipper);
        $this->assertEquals($cep, $quote->zipcodeOrigin);
        $this->assertEquals(1, $quote->offers->count());

        $offer = $quote->offers[0];
        $this->assertEquals($carrier, $offer->carrier->name);
        $this->assertEquals($cnpjCarrier, $offer->carrier->registeredNumber);
        $this->assertEquals($service, $offer->service);
        $this->assertEquals($deliveryDate->format('Y-m-d'), $offer->deliveryTime->estimatedDate->format('Y-m-d'));
        $this->assertEquals($expiration, $offer->expiration);
    }
}
