<?php

declare(strict_types=1);

namespace Afonso\Dns;

class MxResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new MxResourceRecord('example.com', 1, 10, 'mail.example.com');

        $this->assertEquals(0x0F, $record->getType());
    }

    public function testToString()
    {
        $record = new MxResourceRecord('example.com', 1, 10, 'mail.example.com');

        $this->assertEquals('10 mail.example.com', (string) $record);
    }
}
