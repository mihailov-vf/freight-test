<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Integration\Simulation;

use FreteRapido\Data;
use FreteRapido\Simulation\{
    DesiredReturn,
    Dispatcher,
    Recipient,
    Shipper,
    ShippingInfo
};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Data::class)]
#[CoversClass(DesiredReturn::class)]
#[CoversClass(Dispatcher::class)]
#[CoversClass(Recipient::class)]
#[CoversClass(Shipper::class)]
#[CoversClass(ShippingInfo::class)]
class ShippingInfoMappingTest extends TestCase
{
    #[Test]
    public function creating_from_valid_json()
    {
        $cnpj = fake()->cnpj(false);
        $token = str_replace('-', '', fake()->uuid());
        $platformCode = fake()->word();
        $cep = fake()->postcode();

        $recipientCep = fake()->postcode();
        $json = require __DIR__ . '/../../data/shipping_info.php';

        try {
            $shippingInfo = ShippingInfo::from(
                $json
            );
            $shippingInfo->shipper = Shipper::from([
                'registered_number' => $cnpj,
                'token' => $token,
                'platform_code' => $platformCode,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            echo $e->validator->errors();
            $this->fail('ValidationErrors');
        }

        $this->assertEquals($cnpj, $shippingInfo->shipper->registeredNumber);
        $this->assertEquals($token, $shippingInfo->shipper->token);
        $this->assertEquals($platformCode, $shippingInfo->shipper->platformCode);


        $this->assertEquals(str_replace('-', '', $recipientCep), $shippingInfo->recipient->zipcode);
        $this->assertIsInt($shippingInfo->recipient->zipcode);

        $this->assertCount(1, $shippingInfo->dispatchers);
        $this->assertEquals($cnpj, $shippingInfo->dispatchers[0]->registeredNumber);
        $this->assertEquals(str_replace('-', '', $cep), $shippingInfo->dispatchers[0]->zipcode);
        $this->assertIsInt($shippingInfo->dispatchers[0]->zipcode);
        $this->assertCount(1, $shippingInfo->dispatchers[0]->volumes);
    }
}
