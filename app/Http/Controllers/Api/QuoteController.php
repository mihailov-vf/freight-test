<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Data\Quote\QuoteRequest;
use App\Data\Quote\QuoteResponse;
use App\Exceptions\InternalServerException;
use App\Http\Controllers\Controller;
use App\Models\Dispatcher;
use App\Models\Quote;
use FreteRapido\FreteRapido;
use FreteRapido\ServiceError;
use FreteRapido\Simulation\ShippingInfo;
use FreteRapido\Simulation\SimulationType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuoteController extends Controller
{
    public function __construct(private FreteRapido $freteRapido)
    {
    }

    public function __invoke(Request $request, QuoteRequest $quoteRequest): Response
    {
        /** @var Dispatcher */
        $dispatcher = Dispatcher::first() ?? throw new InternalServerException('Verifique as configurações do serviço');

        $quoteRequest->additional([
            'dispatcher' => [
                'registered_number' => $dispatcher->registered_number,
                'address' => [
                    'zipcode' => $dispatcher->address_zipcode,
                ],
            ]
        ]);

        /** @var Quote */
        $quote = Quote::create($quoteRequest->toArray());

        $shippingInfo = ShippingInfo::from([
            'recipient' => [
                'zipcode' => $quoteRequest->recipient->address->zipcode,
            ],
            'dispatchers' => [
                [
                    'registered_number' => $dispatcher->registered_number,
                    'zipcode' => $dispatcher->address_zipcode,
                    'volumes' => $quoteRequest->volumes->toArray(),
                ]
            ],
            'simulation_type' => [SimulationType::Fracionada],
        ]);

        $simulation = $this->freteRapido->simulate($shippingInfo);
        if ($simulation instanceof ServiceError) {
            return $simulation->only('message')
                ->toResponse(request())
                ->setStatusCode($simulation->suggestedCode);
        }

        $quote->addOffersFrom($simulation);
        $quote->save();

        return QuoteResponse::from([
            'offers' => $quote->offers
        ])->toResponse($request);
    }
}
