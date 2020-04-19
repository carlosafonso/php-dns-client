<?php

declare(strict_types=1);

namespace Afonso\Dns;

class PtrResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new PtrResourceRecord('1.2.3.4.in-addr.arpa', 1, 'example.com');

        $this->assertEquals(0x0C, $record->getType());
    }

    public function testToString()
    {
        $record = new PtrResourceRecord('1.2.3.4.in-addr.arpa', 1, 'example.com');

        $this->assertEquals('example.com', (string) $record);
    }
}
