<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Quote extends Data
{
    /**
     * @param Offer[]|DataCollection|null $offers Ofertas de cotações para o ponto de expedição informado
     * @param Volume[]|DataCollection|null $volumes Volumes/itens do ponto de expedição utilizados na cotação
     * @param string[]|null $appliedRules Regras de fretes aplicadas
     */
    public function __construct(
        /** Identificador da cotação/simulação */
        public readonly string $id,

        /** Identificador da requisição */
        public readonly string $requestId,

        /** CNPJ da conta do remetente */
        public readonly string $registeredNumberShipper,

        /** CNPJ do ponto de expedição */
        public readonly string $registeredNumberDispatcher,

        /** CEP do ponto de expedição (origem) */
        public readonly string $zipcodeOrigin,

        #[DataCollectionOf(Offer::class)]
        public readonly ?DataCollection $offers,

        #[DataCollectionOf(Volume::class)]
        public readonly ?DataCollection $volumes,

        public readonly ?array $appliedRules,
    ) {
    }
}
