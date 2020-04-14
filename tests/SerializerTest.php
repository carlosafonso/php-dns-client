<?php

declare(strict_types=1);

namespace Afonso\Dns;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
    protected $serializer;

    public function setUp(): void
    {
        $this->serializer = new Serializer();
    }

    public function testSerializeRequest()
    {
        $request = new Request('example.com', Request::RR_TYPE_A);

        $serialized = $this->serializer->serializeRequest($request);

        $expected = "aa aa 01 00 00 01 00 00 00 00 00 00 07 65 78 61 6d 70 6c 65 03 63 6f 6d 00 00 01 00 01";
        $expected = str_replace(' ', '', $expected);
        $this->assertEquals($expected, bin2hex($serialized));
    }

    public function testSerializeRequestWithDomainWithLabelTooLarge()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one of the labels of the specified domain exceeds the allowed maximum length');

        $request = new Request('this-is-a-domain-name-which-certainly-has-grown-a-bit-too-large-hasnt-it.com', Request::RR_TYPE_A);

        $this->serializer->serializeRequest($request);
    }

    public function testDeserializeResponse()
    {
        $response = 'AA AA 81 80 00 01 00 01 00 00 00 00 07 65 78 61 6D 70 6C 65 03 63 6F 6D 00 00 01 00 01 C0 0C 00 01 00 01 00 00 18 4C 00 04 5D B8 D8 22';
        $response = hex2bin(str_replace(' ', '', $response));

        $response = $this->serializer->deserializeResponse($response);

        $this->assertFalse($response->isAuthoritative());
        $this->assertTrue($response->isRecursionAvailable());
        $this->assertEquals(0x00, $response->getType());
        $records = $response->getResourceRecords();
        $this->assertCount(1, $records);
        $this->assertEquals('example.com', $records[0]->getName());
        $this->assertEquals(0x01, $records[0]->getType());
        $this->assertEquals(6220, $records[0]->getTTL());
        $this->assertEquals('93.184.216.34', $records[0]->getValue());
    }

    public function testDeserializeResponseWithCnameRecord()
    {
        $response = 'cc 5a 81 80 00 01 00 01 00 00 00 01 03 77 77 77'
            . '07 65 78 61 6d 70 6c 65 03 63 6f 6d 00 00 05 00'
            . '01 c0 0c 00 05 00 01 00 00 0e 0f 00 02 c0 10 00'
            . '00 29 02 00 00 00 00 00 00 00';
        $response = hex2bin(str_replace(' ', '', $response));

        $response = $this->serializer->deserializeResponse($response);

        $this->assertFalse($response->isAuthoritative());
        $this->assertTrue($response->isRecursionAvailable());
        $this->assertEquals(0x00, $response->getType());
        $records = $response->getResourceRecords();
        $this->assertCount(1, $records);
        $this->assertEquals(0x05, $records[0]->getType());
        $this->assertEquals(3599, $records[0]->getTTL());
        $this->assertEquals('example.com', $records[0]->getValue());
    }

    public function testDeserializeResponseWithMxRecords()
    {
        $response = '00 f7 81 80 00 01 00 05 00 00 00 01 06 67 6f 6f'
            . '67 6c 65 03 63 6f 6d 00 00 0f 00 01 c0 0c 00 0f'
            . '00 01 00 00 02 19 00 0c 00 0a 05 61 73 70 6d 78'
            . '01 6c c0 0c c0 0c 00 0f 00 01 00 00 02 19 00 09'
            . '00 1e 04 61 6c 74 32 c0 2a c0 0c 00 0f 00 01 00'
            . '00 02 19 00 09 00 14 04 61 6c 74 31 c0 2a c0 0c'
            . '00 0f 00 01 00 00 02 19 00 09 00 28 04 61 6c 74'
            . '33 c0 2a c0 0c 00 0f 00 01 00 00 02 19 00 09 00'
            . '32 04 61 6c 74 34 c0 2a 00 00 29 02 00 00 00 00'
            . '00 00 00';
        $response = hex2bin(str_replace(' ', '', $response));

        $response = $this->serializer->deserializeResponse($response);

        $this->assertFalse($response->isAuthoritative());
        $this->assertTrue($response->isRecursionAvailable());
        $this->assertEquals(0x00, $response->getType());
        $records = $response->getResourceRecords();
        $this->assertCount(5, $records);
        $this->assertEquals(0x0F, $records[0]->getType());
        $this->assertEquals(537, $records[0]->getTTL());
        $this->assertEquals('10 aspmx.l.google.com', $records[0]->getValue());
        $this->assertEquals('30 alt2.aspmx.l.google.com', $records[1]->getValue());
        $this->assertEquals('20 alt1.aspmx.l.google.com', $records[2]->getValue());
        $this->assertEquals('40 alt3.aspmx.l.google.com', $records[3]->getValue());
        $this->assertEquals('50 alt4.aspmx.l.google.com', $records[4]->getValue());
    }

    /*
     * To-Do:
     * - Test deserializing responses with the "label" format
     */
}
