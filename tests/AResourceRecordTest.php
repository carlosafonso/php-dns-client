<?php

declare(strict_types=1);

namespace Afonso\Dns;

class AResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new AResourceRecord('example.com', 1, '1.2.3.4');

        $this->assertEquals(0x01, $record->getType());
    }

    public function testToString()
    {
        $record = new AResourceRecord('example.com', 1, '1.2.3.4');

        $this->assertEquals('1.2.3.4', (string) $record);
    }
}
