<?php

declare(strict_types=1);

namespace FreteRapido;

use Spatie\LaravelData\Data as LaravelData;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\AuthorizedDataPipe;
use Spatie\LaravelData\DataPipes\CastPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\DefaultValuesDataPipe;
use Spatie\LaravelData\DataPipes\MapPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\ValidatePropertiesDataPipe;
use Stringable;

class Data extends LaravelData implements Stringable
{
    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(ValidatePropertiesDataPipe::allTypes())
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }

    public function __toString(): string
    {
        return $this->toJson(JSON_THROW_ON_ERROR);
    }
}
