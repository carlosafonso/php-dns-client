<?php

declare(strict_types=1);

namespace Afonso\Dns;

class ResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetName()
    {
        $record = $this->getMockForAbstractClass(ResourceRecord::class, ['example.com', 123]);

        $this->assertEquals('example.com', $record->getName());
    }

    public function testGetTtl()
    {
        $record = $this->getMockForAbstractClass(ResourceRecord::class, ['example.com', 123]);

        $this->assertEquals(123, $record->getTtl());
    }

    /**
     * @dataProvider validTypesProvider
     */
    public function testIsValidTypeWithValidTypes($type)
    {
        $this->assertTrue(ResourceRecord::isValidType($type));
    }

    public function testIsValidTypeWithInvalidType()
    {
        $this->assertFalse(ResourceRecord::isValidType(0xFFFF));
    }

    public function validTypesProvider()
    {
        return [
            [0x0001],
            [0x0002],
            [0x0005],
            [0x0006],
            [0x000C],
            [0x000F],
            [0x0021],
            [0x001C],
            [0x00FF]
        ];
    }
}
