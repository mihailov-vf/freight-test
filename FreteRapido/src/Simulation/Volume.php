<?php

declare(strict_types=1);

namespace FreteRapido\Simulation;

use FreteRapido\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Volume extends Data
{
    /** @param mixed[]|null $items */
    public function __construct(
        /** Quantidade do mesmo volume/item */
        #[Numeric]
        public readonly int $amount,

        /** Tipo do volume/Categoria do produto (vide tabela de tipos de volumes) */
        #[Numeric]
        public readonly string $category,

        /** SKU do volume/produto informado */
        #[Nullable]
        public readonly ?string $sku,

        /** Tag do volume/produto informado */
        #[Nullable]
        public readonly ?string $tag,

        /** Descrição do produto/item */
        #[Nullable]
        public readonly ?string $description,

        /** Altura em Metros do volume/produto unitário */
        #[Numeric]
        public readonly float $height,

        /** Largura em Metros do volume/produto unitário */
        #[Numeric]
        public readonly float $width,

        /** Comprimento em Metros do volume/produto unitário */
        #[Numeric]
        public readonly float $length,

        /** Valor unitário do volume/item informado */
        #[Numeric]
        public readonly float $unitaryPrice,

        /** Peso unitário (em Kg) do volume/item */
        #[Numeric]
        public readonly float $unitaryWeight,

        /** Consolidar volume? Default: false */
        #[Sometimes]
        #[BooleanType]
        public readonly ?bool $consolidate,

        /** Sobrepor volume sobre outro? Default: false */
        #[Sometimes]
        #[BooleanType]
        public readonly ?bool $overlaid,

        /** Rotacionar/Tombar volume? Default: false */
        #[Sometimes]
        #[BooleanType]
        public readonly ?bool $rotate,

        /**
         * Quantidade de volumes do produto ao qual este volume pertence.
         *
         * Ex.: Este volume pertence a um jogo de cama que é composto por quatro volumes no mesmo SKU, então o campo deve ser preenchido com 4. Usaremos esta informação para agrupar os volumes de um mesmo produto.
         */
        #[Sometimes]
        #[Numeric]
        public readonly ?int $amountVolumes,

        /**
         * Itens consolidados (somento no retorno quando consolidate = true)
         */
        #[Nullable]
        public readonly ?array $items,
    ) {
    }
}
