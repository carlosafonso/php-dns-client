<?php

declare(strict_types=1);

namespace Afonso\Dns;

class Serializer
{
    public function serializeRequest(Request $request): string
    {
        $bytes = [];

        /*
         * Header
         */
        // ID (0x00 - 0x15)
        $bytes = array_merge($bytes, [0xAA, 0xAA]);

        // QR, OPCODE, AA, TC, RD, RA, RCODE (0x16 - 0x31)
        $bytes += array_merge($bytes, [0x01, 0x00]);

        // QDCOUNT (0x32 - 0x47)
        $bytes += array_merge($bytes, [0x00, 0x01]);

        // ANCOUNT (0x48 - 0x63)
        $bytes += array_merge($bytes, [0x00, 0x00]);

        // NSCOUNT (0x64 - 0x79)
        $bytes += array_merge($bytes, [0x00, 0x00]);

        // ARCOUNT (0x80 - 0x95)
        $bytes += array_merge($bytes, [0x00, 0x00]);

        /*
         * Question
         */
        // QNAME
        $qname = [];
        $labels = explode('.', $request->getName());
        foreach ($labels as $label) {
            $labelLength = strlen($label);
            if ($labelLength > 63) {
                throw new \InvalidArgumentException(
                    'At least one of the labels of the specified domain exceeds the allowed maximum length'
                );
            }
            $qname = array_merge($qname, [$labelLength]);
            $qname = array_merge($qname, array_map('ord', str_split($label)));
        }
        $qname = array_merge($qname, [0x00]);
        $bytes += array_merge($bytes, $qname);

        // QTYPE
        $bytes += array_merge($bytes, [0x00, $request->getType()]);

        // QCLASS
        $bytes += array_merge($bytes, [0x00, 0x01]);

        return implode(array_map('chr', $bytes));
    }

    public function deserializeResponse(string $response): Response
    {
        $responseObj = new Response();

        $bytes = array_map('ord', str_split($response));

        // To-Do: check that response is actually a response (QR)

        /*
         * Header
         */
        $isAuthoritative = (bool) ($bytes[2] & 0x04);
        $isRecursionAvailable = (bool) ($bytes[3] & 0x80);
        $type = $bytes[3] & 0x0F;

        $qdCount = ($bytes[4] << 8) | $bytes[5];
        $anCount = ($bytes[6] << 8) | $bytes[7];
        $nsCount = ($bytes[8] << 8) | $bytes[9];
        $arCount = ($bytes[10] << 8) | $bytes[11];

        $responseObj->setAuthoritative($isAuthoritative);
        $responseObj->setRecursionAvailable($isRecursionAvailable);
        $responseObj->setType($type);

        // From now on there are variable-length fields, so we need to keep
        // track of the current byte position.
        $ptr = 12;

        /*
         * Question
         */
        // QNAME
        while ($bytes[$ptr] > 0x00) {
            $labelLength = $bytes[$ptr];
            // Just skip the label for the time being
            // $label = implode('', array_map('chr', array_slice($bytes, $ptr + 1, $labelLength)));
            $ptr += $labelLength + 1;
        }
        $ptr++; // End of field

        // QTYPE
        $ptr += 2;

        // CLASS
        $ptr += 2;

        /*
         * Answer
         */
        for ($i = 0; $i < $anCount; $i++) {
            // NAME
            [$ptr, $name] = $this->readLabels($bytes, $ptr);

            // TYPE
            $type = $bytes[$ptr++] << 8 | $bytes[$ptr++];

            // CLASS
            $ptr += 2;

            // TTL
            $ttl = $bytes[$ptr++] << 24 | $bytes[$ptr++] << 16 | $bytes[$ptr++] << 8 | $bytes[$ptr++];

            // RDATA
            $rdLength = $bytes[$ptr++] << 8 | $bytes[$ptr++];
            switch ($type) {
                case Request::RR_TYPE_A:
                    $value = $bytes[$ptr++] . '.' . $bytes[$ptr++] . '.' . $bytes[$ptr++] . '.' . $bytes[$ptr++];
                    break;
                case Request::RR_TYPE_CNAME:
                    [$ptr, $value] = $this->readLabels($bytes, $ptr);
                    break;
                default:
                    throw new \RuntimeException("Reading responses for resource type '{$type}' is not implemented");
            }

            $record = new ResourceRecord($name, $type, $ttl, $value);
            $responseObj->addResourceRecord($record);
        }

        /*
         * Authority
         */
        // Intentionally skipped

        /*
         * Additional
         */
        // Intentionally skipped

        return $responseObj;
    }

    private function readLabels(array $bytes, int $ptr): array
    {
        $labels = [];
        $format = $bytes[$ptr] & 0xC0;
        if ($format == 0xC0) { // Pointer format
            $offset = ($bytes[$ptr++] << 8 | $bytes[$ptr++]) & 0x3F;
            while ($bytes[$offset] > 0x00) {
                $labelLength = $bytes[$offset];
                $labels[] = implode('', array_map('chr', array_slice($bytes, $offset + 1, $labelLength)));
                $offset += $labelLength + 1;
            }
        } elseif ($format == 0x00) {  // Label format
            throw new \RuntimeException('Reading responses in label format is not implemented');
        } else {
            throw new \RuntimeException("Unrecognized format '${format}' in response");
        }

        return [$ptr, implode('.', $labels)];
    }
}
