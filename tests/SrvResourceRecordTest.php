<?php

declare(strict_types=1);

namespace Afonso\Dns;

class SrvResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new SrvResourceRecord('_http._tcp.example.com', 1, 10, 0, 80, 'www.example.com');

        $this->assertEquals(0x21, $record->getType());
    }

    public function testToString()
    {
        $record = new SrvResourceRecord('_http._tcp.example.com', 1, 10, 0, 80, 'www.example.com');

        $this->assertEquals('10 0 80 www.example.com', (string) $record);
    }
}
