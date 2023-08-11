<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Carrier extends Data
{
    public function __construct(
        /** Identificador único da transportadora */
        public readonly int $reference,

        /** Nome */
        public readonly string $name,

        /** CNPJ */
        public readonly string $registeredNumber,

        /** Inscrição Estadual */
        public readonly string $stateInscription,

        /** URL do logotipo */
        public readonly string $logo,
    ) {
    }
}
