<?php

return [
    'shipper' => [
        'registered_number' => env('CREDENCIAIS_CNPJ'),
        'token' => env('CREDENCIAIS_TOKEN'),
        'platform_code' => env('CREDENCIAIS_CODIGO_PLATAFORMA'),
    ],
    'recipient' => [
        'type' => 0,
        'country' => 'BRA',
        'zipcode' => '01311-000',
    ],
    'dispatchers' => [
        [
            'registered_number' => env('CREDENCIAIS_CNPJ'),
            'zipcode' => env('ENVIO_CEP'),
            'volumes' => [
                [
                    'amount' => 1,
                    'category' => 7,
                    'sku' => 'abc-teste-123',
                    'height' => 0.2,
                    'width' => 0.2,
                    'length' => 0.2,
                    'unitary_price' => 349,
                    'unitary_weight' => 5,
                ]
            ]
        ]
    ],
    'simulation_type' => [
        0
    ],
];
