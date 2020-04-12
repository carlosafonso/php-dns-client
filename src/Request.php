<?php

declare(strict_types=1);

namespace Afonso\Dns;

class Request
{
    const RR_TYPE_A = 0x0001;
    const RR_TYPE_NS = 0x0002;
    const RR_TYPE_CNAME = 0x0005;
    const RR_TYPE_SOA = 0x0006;
    const RR_TYPE_WKS = 0x000B;
    const RR_TYPE_PTR = 0x000C;
    const RR_TYPE_MX = 0x000F;
    const RR_TYPE_SRV = 0x0021;
    const RR_TYPE_AAAA = 0x001C;
    const RR_TYPE_ANY = 0x00FF;

    protected $name;

    protected $type;

    public function __construct(string $name, int $type)
    {
        if (!$this->isValidRequestType($type)) {
            throw new \InvalidArgumentException("'${type}' is not a valid DNS request type");
        }

        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }

    private function isValidRequestType(int $type): bool
    {
        $validTypes = [
            self::RR_TYPE_A,
            self::RR_TYPE_NS,
            self::RR_TYPE_CNAME,
            self::RR_TYPE_SOA,
            self::RR_TYPE_WKS,
            self::RR_TYPE_PTR,
            self::RR_TYPE_MX,
            self::RR_TYPE_SRV,
            self::RR_TYPE_AAAA,
            self::RR_TYPE_ANY
        ];
        return in_array($type, $validTypes);
    }
}
