<?php

namespace Kaizen\Components\Config\Tests\Schema\Node;

use Kaizen\Components\Config\Exception\InvalidNodeTypeException;
use Kaizen\Components\Config\Schema\Node\ArrayNode;
use Kaizen\Components\Config\Schema\Prototype\ConfigPrototypeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ArrayNodeTest extends TestCase
{
    public function testValidateType(): void
    {
        $node = new ArrayNode('array');

        $node->validateType(['string', 123, true]);

        self::assertEquals('array', $node->getKey());
    }

    public static function invalidValuesProvider(): \Iterator
    {
        yield [123];
        yield [12.3];
        yield ['string'];
        yield [true];
    }

    #[DataProvider('invalidValuesProvider')]
    public function testWithInvalidValue(mixed $value): void
    {
        $node = new ArrayNode('array');

        $this->expectException(InvalidNodeTypeException::class);
        $node->validateType($value);
    }

    public function testPrototypeValidateIsCalled(): void
    {
        $value = [
            'parameter' => 'value'
        ];

        $prototypeMock = $this->createMock(ConfigPrototypeInterface::class);
        $prototypeMock->expects(self::once())
            ->method('validatePrototype')
            ->with($value);

        $node = new ArrayNode('array', $prototypeMock);
        $node->validateType($value);
    }
}