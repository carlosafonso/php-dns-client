<?php

declare(strict_types=1);

namespace Afonso\Dns;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider validTypesProvider
     */
    public function testSetType($type)
    {
        $response = new Response();

        $response->setType($type);

        $this->assertEquals($type, $response->getType());
    }

    public function testCreatingAnInvalidRequest()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Response())->setType(0xFF);
    }

    public function validTypesProvider()
    {
        return [
            [0x00],
            [0x01],
            [0x02],
            [0x03],
            [0x04],
            [0x05]
        ];
    }
}
