<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Filled;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class ShippingInfo extends Data
{
    #[Sometimes]
    #[Required]
    public ?Shipper $shipper;

    /** @param Dispatcher[] $dispatchers */
    public function __construct(
        public readonly Recipient $recipient,
        #[Filled]
        #[DataCollectionOf(Dispatcher::class)]
        public readonly DataCollection $dispatchers,

        /** Canal de venda */
        public readonly ?string $channel,

        /**
         * Filtro de resultados
         *
         * 1 = Retornar somente a oferta com menor preço
         * 2 = Retornar somente a oferta com menor prazo de entrega
         * 3 = Retornar somente a oferta com menor preço e a de menor prazo (caso uma oferta possua menor preço e prazo, apenas ela retornará)
         */
        #[Sometimes]
        #[Numeric]
        #[In([1, 2, 3])]
        public readonly ?int $filter,

        /** Limite de resultados */
        #[Sometimes]
        #[Numeric]
        public readonly ?int $limit,

        /** Identificador externo da cotação na plataforma */
        public readonly ?string $identification,

        /** Calcular frete reverso */
        #[Sometimes]
        #[BooleanType]
        public readonly ?bool $reverse,

        /**
         * 0 = Fracionada
         * 1 = Lotação
         */
        public readonly array $simulationType,
        #[Sometimes]
        public readonly ?DesiredReturn $returns,
    ) {
    }
}
