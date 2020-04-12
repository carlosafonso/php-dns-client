<?php

declare(strict_types=1);

namespace Afonso\Dns;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
    public function testSerializeRequest()
    {
        $serializer = new Serializer();
        $request = new Request('example.com', Request::RR_TYPE_A);

        $serialized = $serializer->serializeRequest($request);

        $expected = "aa aa 01 00 00 01 00 00 00 00 00 00 07 65 78 61 6d 70 6c 65 03 63 6f 6d 00 00 01 00 01";
        $expected = str_replace(' ', '', $expected);
        $this->assertEquals($expected, bin2hex($serialized));
    }

    public function testSerializeRequestWithDomainWithLabelTooLarge()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one of the labels of the specified domain exceeds the allowed maximum length');

        $serializer = new Serializer();
        $request = new Request('this-is-a-domain-name-which-certainly-has-grown-a-bit-too-large-hasnt-it.com', Request::RR_TYPE_A);

        $serializer->serializeRequest($request);
    }

    // To-Do: test that domains with labels greater than 63 chars throw errors
}