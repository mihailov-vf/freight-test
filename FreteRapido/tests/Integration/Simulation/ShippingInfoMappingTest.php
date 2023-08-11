<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Unit\Simulation;

use FreteRapido\Simulation\Shipper;
use FreteRapido\Simulation\ShippingInfo;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
        $json = <<<JSON
{
  "recipient": {
    "type": 0,
    "zipcode": "{$recipientCep}"
  },
  "dispatchers": [
    {
      "registered_number": "{$cnpj}",
      "zipcode": "{$cep}",
      "total_price": 0.0,
      "volumes": [
        {
          "amount": 0,
          "amount_volumes": 0,
          "category": 1,
          "sku": "",
          "tag": "",
          "description": "",
          "height": 0.0,
          "width": 0.0,
          "length": 0.0,
          "unitary_price": 0.0,
          "unitary_weight": 0.0,
          "consolidate": false,
          "overlaid": false,
          "rotate": false
        }
      ]
    }
  ],
  "channel": "",
  "filter": 1,
  "limit": 0,
  "identification": "",
  "reverse": false,
  "simulation_type": [
    0
  ],
  "returns": {
    "composition": false,
    "volumes": false,
    "applied_rules": false
  }
}
JSON;

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
