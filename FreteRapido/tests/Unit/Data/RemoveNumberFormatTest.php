<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Unit\Data;

use FreteRapido\Data\RemoveNumberFormat;
use PHPUnit\Framework\Attributes\{
    CoversClass,
    DataProvider,
    Test
};
use PHPUnit\Framework\TestCase;
use Spatie\LaravelData\Support\DataProperty;
use stdClass;
use Stringable;

class StringableStub implements Stringable
{
    public function __construct(public readonly string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
};

#[CoversClass(RemoveNumberFormat::class)]
class RemoveNumberFormatTest extends TestCase
{
    public static function prepareData(): array
    {
        return [
            ['1', 1],
            ['1.0', 10],
            ['1.2', 12],
            ['-2', 2],
            ['3-3', 33],
            [new StringableStub('1-2-3'), 123],
            ['', 0],
        ];
    }

    #[Test]
    #[DataProvider('prepareData')]
    public function cast_to_type(string|Stringable $initialValue, int $expectedValue)
    {
        $caster = new RemoveNumberFormat();

        $result = $caster->cast($this->createMock(DataProperty::class), $initialValue, []);

        $this->assertSame($expectedValue, $result);
    }

    public static function prepareStringData(): array
    {
        return [
            ['1.2', '12'],
            ['-2', '2'],
            ['3-3', '33'],
            [new StringableStub('1-2-3'), '123'],
            ['', ''],
        ];
    }

    #[Test]
    #[DataProvider('prepareStringData')]
    public function just_remove_format(string|Stringable $initialValue, string $expectedValue)
    {
        $caster = new RemoveNumberFormat(forceType: false);

        $result = $caster->cast($this->createMock(DataProperty::class), $initialValue, []);

        $this->assertSame($expectedValue, $result);
    }

    public static function prepareInvalidData(): array
    {
        return [
            [1.2],
            [new stdClass()],
            [null],
        ];
    }

    #[Test]
    #[DataProvider('prepareInvalidData')]
    public function exception_on_invalid_input($value)
    {
        $foundType = get_debug_type($value);
        $exception = new \InvalidArgumentException("Expected value of type 'string' to cast found {$foundType}.");
        $caster = new RemoveNumberFormat();

        $this->expectExceptionObject($exception);

        $result = $caster->cast($this->createMock(DataProperty::class), $value, []);
    }
}
