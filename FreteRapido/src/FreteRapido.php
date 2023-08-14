<?php

declare(strict_types=1);

namespace FreteRapido;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use FreteRapido\Simulation\Shipper;
use FreteRapido\Simulation\Simulation;
use FreteRapido\Simulation\ShippingInfo;
use Psr\Http\Message\ResponseInterface;
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
        /** @phpstan-ignore-next-line */
        return new Request($method, $uri, body: $body);
    }

    private function checkResponse(ResponseInterface $response): ?ServiceError
    {
        $serviceStatusCode = $response->getStatusCode();
        if ($serviceStatusCode < 400) {
            return null;
        }

        $suggestedStatusCode = $serviceStatusCode;
        $message = 'Houve um erro ao enviar a requisição ao serviço externo. Por favor, verifique os dados enviados e tente novamente.';

        // TODO: investigar e descrever novos tipos de erros
        if ($serviceStatusCode >= 500) {
            $message = 'Houve um erro com o serviço externo. Por favor, após alguns instantes tente novamente.';
        }

        return new ServiceError(
            $serviceStatusCode,
            $response->getReasonPhrase(),
            $response->getBody()->getContents(),
            $suggestedStatusCode,
            $message,
        );
    }
}
