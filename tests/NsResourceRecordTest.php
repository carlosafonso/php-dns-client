<?php

declare(strict_types=1);

namespace Afonso\Dns;

class NsResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new NsResourceRecord('example.com', 1, 'ns01.example.com');

        $this->assertEquals(0x02, $record->getType());
    }

    public function testToString()
    {
        $record = new NsResourceRecord('example.com', 1, 'ns01.example.com');

        $this->assertEquals('ns01.example.com', (string) $record);
    }
}
