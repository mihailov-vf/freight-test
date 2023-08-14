<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use DateTimeImmutable;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Tempo de entrega é o somatório do (Tempo de entrega original) + o(s) dia(s) que podem ser acrescentados a partir
 * da regra de frete criada.
 */
#[MapName(SnakeCaseMapper::class)]
class DeliveryTime extends Data
{
    public function __construct(
        /** Tempo de entrega em dias */
        public readonly ?int $days = null,

        /** Tempo de entrega em horas */
        public readonly ?int $hours = null,

        /** Tempo de entrega em minutos */
        public readonly ?int $minutes = null,

        /**
         * Data prevista de entrega pela transportadora, desconsiderando finais de semana, feriados nacionais do BRA e
         * feriados calculados com a Páscoa.
         */
        public readonly DateTimeImmutable $estimatedDate,
    ) {
    }
}
