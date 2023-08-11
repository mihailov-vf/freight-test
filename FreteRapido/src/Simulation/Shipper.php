<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use FreteRapido\Data\RemoveNumberFormat;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/** Objeto com dados da conta do remetente */
#[MapName(SnakeCaseMapper::class)]
final class Shipper extends Data
{
    public function __construct(
        /** CNPJ da conta registrada na Frete Rápido */
        #[Rule('cnpj')]
        #[WithCast(RemoveNumberFormat::class, forceType: false)]
        public readonly string $registeredNumber,

        /** Token de integração */
        #[Size(32)]
        public readonly string $token,

        /** Código da plataforma integrada. */
        public readonly string $platformCode,
    ) {
    }
}
