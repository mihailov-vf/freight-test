<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/** Retornos que desejar obter */
#[MapName(SnakeCaseMapper::class)]
final class DesiredReturn extends Data
{
    public function __construct(
        /** Retornar a composição de cálculo do frete */
        #[Sometimes]
        #[Required]
        #[BooleanType]
        public readonly ?bool $composition = false,

        /** Retornar os volumes utilizados na cotação (consolidados ou não) */
        #[Sometimes]
        #[Required]
        #[BooleanType]
        public readonly ?bool $volumes = false,

        /** Retornar regras de fretes aplicadas */
        #[Sometimes]
        #[Required]
        #[BooleanType]
        public readonly ?bool $appliedRules = false,
    ) {
    }
}
