<?php

return <<<JSON
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
