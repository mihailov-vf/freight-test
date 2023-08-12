<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Unit\Data;

use DateTime;
use DateTimeInterface;
use FreteRapido\Data\DateTimeConstructorParse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Spatie\LaravelData\Support\DataProperty;

class DateTimeConstructorParseTest extends TestCase
{
    #[Test]
    #[TestWith(['2023-01-01T01:01:01.000001', new DateTime('2023-01-01T01:01:01.000001')])]
    // Format returned on Frete Rápido
    #[TestWith(['2023-01-01T01:01:01.000000001Z', new DateTime('2023-01-01T01:01:01.000000001Z')])]
    public function parse_date_without_formats(string $initialValue, DateTimeInterface $expectedValue)
    {
        $caster = new DateTimeConstructorParse();

        $result = $caster->cast($this->createMock(DataProperty::class), $initialValue, []);

        $this->assertEquals($expectedValue, $result);
    }

    #[Test]
    #[TestWith(['123456#2222'])]
    public function invalid_formats(string $initialValue)
    {
        $caster = new DateTimeConstructorParse();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format');
        $result = $caster->cast($this->createMock(DataProperty::class), $initialValue, []);
    }
}
