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

        [$_, $qdCount] = $this->readInt($bytes, 4, 2);
        [$_, $anCount] = $this->readInt($bytes, 6, 2);
        [$_, $nsCount] = $this->readInt($bytes, 8, 2);
        [$_, $arCount] = $this->readInt($bytes, 10, 2);

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
            [$ptr, $name] = $this->readNameField($bytes, $ptr);

            // TYPE
            [$ptr, $type] = $this->readInt($bytes, $ptr, 2);

            // CLASS
            $ptr += 2;

            // TTL
            [$ptr, $ttl] = $this->readInt($bytes, $ptr, 4);

            // RDATA
            [$ptr, $rdLength] = $this->readInt($bytes, $ptr, 2);
            switch ($type) {
                case Request::RR_TYPE_A:
                    $value = $bytes[$ptr++] . '.' . $bytes[$ptr++] . '.' . $bytes[$ptr++] . '.' . $bytes[$ptr++];
                    break;
                case Request::RR_TYPE_NS:
                case Request::RR_TYPE_CNAME:
                case Request::RR_TYPE_PTR:
                    [$ptr, $value] = $this->readNameField($bytes, $ptr);
                    break;
                case Request::RR_TYPE_SOA:
                    [$ptr, $primaryNs] = $this->readNameField($bytes, $ptr);
                    [$ptr, $adminMb] = $this->readNameField($bytes, $ptr);
                    [$ptr, $serialNo] = $this->readInt($bytes, $ptr, 4);
                    [$ptr, $refreshInterval] = $this->readInt($bytes, $ptr, 4);
                    [$ptr, $retryInterval] = $this->readInt($bytes, $ptr, 4);
                    [$ptr, $expirationLimit] = $this->readInt($bytes, $ptr, 4);
                    [$ptr, $minimumTtl] = $this->readInt($bytes, $ptr, 4);
                    $value = "{$primaryNs} {$adminMb} {$serialNo} {$refreshInterval}"
                        . " {$retryInterval} {$expirationLimit} {$minimumTtl}";
                    break;
                case Request::RR_TYPE_MX:
                    [$ptr, $preference] = $this->readInt($bytes, $ptr, 2);
                    [$ptr, $exchanger] = $this->readNameField($bytes, $ptr);
                    $value = "{$preference} {$exchanger}";
                    break;
                case Request::RR_TYPE_AAAA:
                    $packed = '';
                    for ($i = 0; $i < 16; $i++) {
                        $packed .= chr($bytes[$ptr++]);
                    }
                    $value = inet_ntop($packed);
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

    private function readNameField(array $bytes, int $ptr): array
    {
        while (true) {
            // Zero byte means we hit end of name field. Break the loop.
            if ($bytes[$ptr] == 0) {
                $ptr++;
                break;
            }

            $format = $bytes[$ptr] & 0xC0;
            $length = $offset = 0;

            if ($format == 0xC0) { // Pointer format
                $offset = ($bytes[$ptr++] << 8 | $bytes[$ptr++]) & 0x3F;
                [$_, $label] = $this->readNameField($bytes, $offset);
                $labels[] = $label;
                // Pointer ends the name field. Break the loop.
                break;
            } elseif ($format == 0x00) {  // Label format
                $length = $bytes[$ptr++] & 0x3F;
                $labels[] = implode('', array_map('chr', array_slice($bytes, $ptr, $length)));
                $ptr += $length;
            } else {
                throw new \RuntimeException("Unrecognized format '${format}' in response");
            }
        }

        return [$ptr, implode('.', $labels)];
    }

    /**
     * Reads and returns an integer from the given byte array, at the specified
     * offset and with the specified length in bytes.
     *
     * @param int[] $byteArray
     * @param int $ptr
     * @param int $bytes
     * @return int[] An array of size 2, the first item being the updated
     * pointer (after the read operation) and the second item being the actual
     * integer that was read.
     */
    private function readInt(array $byteArray, int $ptr, int $bytes): array
    {
        $int = 0;
        for ($i = $bytes - 1; $i >= 0; $i--) {
            $int |= $byteArray[$ptr++] << 8 * $i;
        }
        return [$ptr, $int];
    }
}
