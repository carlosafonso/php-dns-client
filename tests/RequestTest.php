<?php

declare(strict_types=1);

namespace Afonso\Dns;

use InvalidArgumentException;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatingAValidRequest()
    {
        $request = new Request('foo.com', 0x0001);

        $this->assertEquals('foo.com', $request->getName());
        $this->assertEquals(0x0001, $request->getType());
    }

    public function testCreatingAnInvalidRequest()
    {
        $this->expectException(InvalidArgumentException::class);

        new Request('foo.com', 0xFFFF);
    }
}
