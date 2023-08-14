<?php

use FreteRapido\Simulation\Carrier;
use FreteRapido\Simulation\Offer;
use FreteRapido\Simulation\Simulation;

$offersNumber ??= 1;
$offers = [];

for ($i = 0; $i < $offersNumber; $i++) {
    $offers[] = Offer::from([
        'offer' => $i,
        'carrier' => new Carrier(
            fake()->randomNumber(),
            fake()->company(),
            fake()->cnpj(false),
            fake()->buildingNumber(),
            fake()->url()
        ),
        'service' => fake()->word(),
        'deliveryTime' => [
            'estimated_date' => fake()->dateTime('next week')->format(DATE_ATOM)
        ],
        'originalDeliveryTime' => [
            'estimated_date' => fake()->dateTime('next week')->format(DATE_ATOM)
        ],
        'expiration' => fake()->dateTime('tomorrow')->format(DATE_ATOM),
        'costPrice' => fake()->randomFloat(min: 0, max: 200),
        'finalPrice' => fake()->randomFloat(min: 0, max: 200),
        'weights' => [
            'real' => fake()->randomFloat(min: 0, max: 200),
            'used' => fake()->randomFloat(min: 0, max: 200),
        ],
    ]);
}

return Simulation::from([
    'dispatchers' => [
        [
            'id' => fake()->uuid(),
            'requestId' => fake()->uuid(),
            'registered_number_shipper' => fake()->cnpj(false),
            'registered_number_dispatcher' => fake()->cnpj(false),
            'zipcode_origin' => fake()->postcode(),
            'offers' => $offers
        ]
    ]
]);
