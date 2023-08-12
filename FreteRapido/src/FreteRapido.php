<?php

declare(strict_types=1);

namespace FreteRapido;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use FreteRapido\Simulation\Shipper;
use FreteRapido\Simulation\Simulation;
use FreteRapido\Simulation\ShippingInfo;
use Stringable;

enum FreteRapidoApiVersion: string
{
    case V3 = 'https://sp.freterapido.com/api/v3';
}

class FreteRapido
{
    public function __construct(
        private Credentials $credentials,
        private Client $httpClient,
    ) {
    }

    public function simulate(ShippingInfo $shipping): Simulation|ServiceError
    {
        $shipping->shipper = Shipper::from([
            'registered_number' => $this->credentials->cnpj,
            'token' => $this->credentials->token,
            'platform_code' => $this->credentials->codigoPlataforma,
        ]);
        $request = $this->request(FreteRapidoApiVersion::V3, 'POST', 'quote/simulate', $shipping);
        $response = $this->httpClient->sendRequest($request);

        return $this->checkResponse($response) ?? Simulation::from($response->getBody()->getContents());
    }

    private function request(
        FreteRapidoApiVersion $apiVersion,
        string $method,
        string $endpoint,
        string|Stringable $body = null
    ): Request {
        $uri = "{$apiVersion->value}/$endpoint";
        return new Request($method, $uri, body: $body);
    }

    private function checkResponse(Response $response): ?ServiceError
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode < 400) {
            return null;
        }

        return new ServiceError($statusCode, $response->getReasonPhrase(), $response->getBody()->getContents());
    }
}
