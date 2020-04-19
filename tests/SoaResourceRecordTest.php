<?php

declare(strict_types=1);

namespace Afonso\Dns;

class SoaResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new SoaResourceRecord(
            'example.com',
            1,
            'ns01.example.com',
            'hostmaster.example.com',
            111,
            222,
            333,
            444,
            555
        );

        $this->assertEquals(0x06, $record->getType());
    }

    public function testToString()
    {
        $record = new SoaResourceRecord(
            'example.com',
            1,
            'ns01.example.com',
            'hostmaster.example.com',
            111,
            222,
            333,
            444,
            555
        );

        $this->assertEquals(
            'ns01.example.com hostmaster.example.com 111 222 333 444 555',
            (string) $record
        );
    }
}
