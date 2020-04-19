<?php

declare(strict_types=1);

namespace Afonso\Dns;

class CnameResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new CnameResourceRecord('www.example.com', 1, 'server01.example.com');

        $this->assertEquals(0x05, $record->getType());
    }

    public function testToString()
    {
        $record = new CnameResourceRecord('www.example.com', 1, 'server01.example.com');

        $this->assertEquals('server01.example.com', (string) $record);
    }
}
