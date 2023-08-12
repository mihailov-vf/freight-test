<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Integration\Simulation;

use DateTimeImmutable;
use FreteRapido\Simulation\Simulation;
use PHPUnit\Framework\Attributes\Test;
use SplFileObject;
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

    #[Test]
    public function creating_from_real_json()
    {
        $json = new SplFileObject(__DIR__ . '/simulation_real_return.json');
        // The default Datetime caster can't deal with this format
        $offersExpirationsDate = array_map(
            static fn ($value) => new DateTimeImmutable($value),
            [
                "2023-09-11T03:43:53.512185969Z",
                "2023-09-11T03:43:53.512200062Z",
                "2023-09-11T03:43:53.512198750Z",
                "2023-09-11T03:43:53.512177336Z",
                "2023-09-11T03:43:53.512188481Z",
            ]
        );

        try {
            $simulation = Simulation::from(
                $json->fread($json->getSize())
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            echo $e->validator->errors();
            $this->fail('ValidationErrors');
        }

        $this->assertIsObject($simulation);
        foreach ($offersExpirationsDate as $n => $expectedDate) {
            $this->assertEquals($expectedDate, $simulation->dispatchers[0]->offers[$n]->expiration);
        }
    }
}
