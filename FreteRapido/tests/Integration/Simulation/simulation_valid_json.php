<?php

/**
 * @var DateTimeInterface $deliveryDate
 * @var DateTimeInterface $expiration
 */

$deliveryDiff = $deliveryDate->diff(new DateTime());

return <<<JSON
{
    "dispatchers": [
        {
            "id": "{$id}",
            "request_id": "{$requestId}",
            "registered_number_shipper": "{$cnpj}",
            "registered_number_dispatcher": "{$cnpj}",
            "zipcode_origin": "{$cep}",
            "offers": [
                {
                    "offer": 0,
                    "simulation_type": 0,
                    "carrier": {
                        "reference": 0,
                        "name": "{$carrier}",
                        "registered_number": "{$cnpjCarrier}",
                        "state_inscription": "1234",
                        "logo": "http://localhost"
                    },
                    "service": "{$service}",
                    "delivery_time": {
                        "days": {$deliveryDiff->d},
                        "hours": {$deliveryDiff->h},
                        "minutes": {$deliveryDiff->i},
                        "estimated_date": "{$deliveryDate->format('Y-m-d')}"
                    },
                    "expiration": "{$expiration->format('c')}",
                    "cost_price": 0.0,
                    "final_price": 0.0,
                    "weights": {
                        "real": 1.0,
                        "cubed": 1.0,
                        "used": 1.0
                    },
                    "original_delivery_time": {
                        "days": {$deliveryDiff->d},
                        "hours": {$deliveryDiff->h},
                        "minutes": {$deliveryDiff->i},
                        "estimated_date": "{$deliveryDate->format('Y-m-d')}"
                    },
                    "identifier": "asd"
                }
            ],
            "volumes": [
                {
                    "category": "0",
                    "sku": "",
                    "tag": "",
                    "description": "",
                    "amount": 1,
                    "width": 1.0,
                    "height": 1.0,
                    "length": 1.0,
                    "unitary_weight": 1.0,
                    "unitary_price": 1.0,
                    "amount_volumes": 1.0,
                    "consolidate": false,
                    "overlaid": false,
                    "rotate": false,
                    "items": []
                }
            ]
        }
    ]
}
JSON;
