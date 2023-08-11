<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use Spatie\LaravelData\Data;

/** Pesos utilizados na cotação */
class Weights extends Data
{
    public function __construct(
        /** Peso real */
        public readonly float $real,

        /** Peso cubado */
        public readonly ?float $cubed,

        /** Peso usado */
        public readonly float $used,
    ) {
    }
}
