<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use FreteRapido\Data\RemoveNumberFormat;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

enum RecipientType: int
{
    case PessoaFisica = 0;
    case PessoaJuridica = 1;
}

/** Objeto com alguns dados do destinatário */
#[MapName(SnakeCaseMapper::class)]
final class Recipient extends Data
{
    public const DEFAULT_COUNTRY = 'BRA';

    public function __construct(
        /** Registro federal do destinatário (CPF ou CNPJ) */
        #[Sometimes]
        #[Rule('cpf_ou_cnpj')]
        #[WithCast(RemoveNumberFormat::class, forceType: false)]
        public readonly ?string $registeredNumber,

        /** Registro estadual do destinatário (Inscrição Estadual) */
        #[Nullable]
        public readonly ?string $stateInscription,

        /** CEP do destinatário */
        #[Regex('/\d{5}-?\d{3}/')]
        #[WithCast(RemoveNumberFormat::class)]
        public readonly int $zipcode,

        /**
         * Tipo de destinatário
         *
         * 0 = Pessoa Física
         * 1 = Pessoa Jurídica
         */
        #[In([0, 1])]
        public readonly RecipientType $type = RecipientType::PessoaFisica,

        /** Para operações no Brasil, informar apenas BRA */
        #[Sometimes]
        #[Alpha]
        #[Size(3)]
        public readonly ?string $country = self::DEFAULT_COUNTRY,
    ) {
    }
}
