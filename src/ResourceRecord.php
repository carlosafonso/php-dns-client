<?php

declare(strict_types=1);

namespace Afonso\Dns;

class ResourceRecord
{
    const TYPE_A = 0x0001;
    const TYPE_NS = 0x0002;
    const TYPE_CNAME = 0x0005;
    const TYPE_SOA = 0x0006;
    const TYPE_PTR = 0x000C;
    const TYPE_MX = 0x000F;
    const TYPE_SRV = 0x0021;
    const TYPE_AAAA = 0x001C;
    const TYPE_ANY = 0x00FF;

    protected $name;

    protected $type;

    protected $ttl;

    protected $value;

    public function __construct(string $name, int $type, int $ttl, string $value)
    {
        $this->name = $name;
        $this->type = $type;
        $this->ttl = $ttl;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isValidType(int $type): bool
    {
        $validTypes = [
            static::TYPE_A,
            static::TYPE_NS,
            static::TYPE_CNAME,
            static::TYPE_SOA,
            static::TYPE_PTR,
            static::TYPE_MX,
            static::TYPE_SRV,
            static::TYPE_AAAA,
            static::TYPE_ANY
        ];
        return in_array($type, $validTypes);
    }
}
