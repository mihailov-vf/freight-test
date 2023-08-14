<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use FreteRapido\Data\RemoveNumberFormat;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Filled;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Dispatcher extends Data
{
    /**
     * @param Volume[]|DataCollection<string,Volume> $volumes
     */
    public function __construct(
        /** CNPJ do expedidor, caso não tenha expedidor informar o mesmo CNPJ utilizado em shipper */
        #[Rule('cnpj')]
        #[WithCast(RemoveNumberFormat::class, forceType: false)]
        public readonly string $registeredNumber,

        /** CEP de origem do expedidor */
        #[Regex('/\d{5}-?\d{3}/')]
        #[WithCast(RemoveNumberFormat::class)]
        public readonly int $zipcode,

        /** Preço total do pedido. Se informado, substituirá proporcionalmente o valor informado nos volumes. */
        #[Nullable]
        #[Numeric]
        public readonly ?float $totalPrice,

        /**
         * Dados dos volumes do ponto de expedição
         */
        #[Filled]
        #[DataCollectionOf(Volume::class)]
        public readonly DataCollection $volumes,
    ) {
    }
}
