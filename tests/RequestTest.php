<?php

declare(strict_types=1);

namespace Afonso\Dns;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatingARequestWithValidType()
    {
        $request = new Request('foo.com', 0x0001);

        $this->assertEquals('foo.com', $request->getName());
        $this->assertEquals(0x0001, $request->getType());
    }

    public function testCreatingARequestWithInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "'65535' is not a valid DNS resource record type"
        );

        new Request('foo.com', 0xFFFF);
    }
}
