<?php

declare(strict_types=1);

namespace Afonso\Dns;

class AaaaResourceRecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $record = new AaaaResourceRecord('example.com', 1, '::1');

        $this->assertEquals(0x1C, $record->getType());
    }

    public function testToString()
    {
        $record = new AaaaResourceRecord('example.com', 1, '::1');

        $this->assertEquals('::1', (string) $record);
    }
}
