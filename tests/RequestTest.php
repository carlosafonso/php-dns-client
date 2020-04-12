<?php

declare(strict_types=1);

namespace Afonso\Dns;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider validTypesProvider
     */
    public function testCreatingAValidRequest($type)
    {
        $request = new Request('foo.com', 0x0001);

        $this->assertEquals('foo.com', $request->getName());
        $this->assertEquals(0x0001, $request->getType());
    }

    public function testCreatingAnInvalidRequest()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Request('foo.com', 0xFFFF);
    }

    public function validTypesProvider()
    {
        return [
            [0x0001],
            [0x0002],
            [0x0005],
            [0x0006],
            [0x000B],
            [0x000C],
            [0x000F],
            [0x0021],
            [0x001C],
            [0x00FF]
        ];
    }
}
