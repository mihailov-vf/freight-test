<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use DateTimeImmutable;
use FreteRapido\Data\DateTimeConstructorParse;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Offer extends Data
{
    public function __construct(
        /** Posição da oferta */
        public readonly int $offer,

        /** Tipo da simulação */
        public readonly ?int $simulationType,

        /** Metadados da transportadora */
        public readonly Carrier $carrier,

        /** Nome do serviço */
        public readonly string $service,

        /** Código do serviço */
        public readonly ?string $serviceCode,

        /** Descrição do serviço */
        public readonly ?string $serviceDescription,
        public readonly DeliveryTime $deliveryTime,

        /** Tempo de entrega sem a aplicação de regras de frete, fornecido via tabela ou na API da transportadora */
        public readonly DeliveryTime $originalDeliveryTime,

        /** Campo para identificação da oferta do lado da transportadora, somente cotações por integração */
        public readonly ?string $identifier,

        /** Prazo de expiração da tabela calculada */
        #[WithCast(DateTimeConstructorParse::class)]
        public readonly DateTimeImmutable $expiration,

        /** Preço de custo da cotação */
        public readonly float $costPrice,

        /** Preço final da cotação (com regra de frete) */
        public readonly float $finalPrice,
        public readonly Weights $weights,
    ) {
    }
}
