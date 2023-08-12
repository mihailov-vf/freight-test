<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Integration\Simulation;

use DateTimeImmutable;
use FreteRapido\Data;
use FreteRapido\Simulation\{
    Simulation,
    Quote,
    Offer,
    Volume,
    Carrier,
    DeliveryTime,
    Weights
};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SplFileObject;
use Tests\TestCase;

#[CoversClass(Carrier::class)]
#[CoversClass(Data::class)]
#[CoversClass(DeliveryTime::class)]
#[CoversClass(Offer::class)]
#[CoversClass(Quote::class)]
#[CoversClass(Simulation::class)]
#[CoversClass(Volume::class)]
#[CoversClass(Weights::class)]
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
        $json = new SplFileObject(dirname(__DIR__) . '/../data/simulation_real_response.json');
        $jsonString = $json->fread($json->getSize());
        $jsonData = json_decode($jsonString, true);

        try {
            $simulation = Simulation::from($jsonString);
        } catch (\Illuminate\Validation\ValidationException $e) {
            echo $e->validator->errors();
            $this->fail('ValidationErrors');
        }

        $this->assertIsObject($simulation);
        $this->assertNotEmpty($simulation->dispatchers);
        $offers = $simulation->dispatchers[0]->offers;
        $this->assertNotEmpty($offers);
        foreach ($offers as $n => $offer) {
            // The default Datetime caster can't deal with the input format
            // This data goes down to the nanoseconds.
            $expectedDate = $jsonData['dispatchers'][0]['offers'][$n]['expiration'];
            // Checking precision to the microseconds
            // I could not find ways to increase the accuracy of the date formatter.
            $this->assertStringContainsString($offers[$n]->expiration->format('Y-m-d\TH:i:s.u'), $expectedDate);
        }
    }
}
