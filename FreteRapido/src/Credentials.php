<?php

declare(strict_types=1);

namespace FreteRapido;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Credentials extends Data
{
    public function __construct(
        /** CNPJ que possui cadastro na Frete Rápido */
        #[Rule('cnpj')]
        public readonly string $cnpj,

        /** Chave de acesso gerada no seu Painel Frete Rápido */
        #[Size(32)]
        public readonly string $token,
        public readonly string $codigoPlataforma,
    ) {
    }
}
