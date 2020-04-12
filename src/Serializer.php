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
                throw new \InvalidArgumentException('At least one of the labels of the specified domain exceeds the allowed maximum length');
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

        // return $id;
        return implode(array_map('chr', $bytes));
    }
}
