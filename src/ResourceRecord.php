<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record.
 */
abstract class ResourceRecord implements ResourceRecordInterface
{
    /**
     * @var int
     */
    const TYPE_A = 0x0001;

    /**
     * @var int
     */
    const TYPE_NS = 0x0002;

    /**
     * @var int
     */
    const TYPE_CNAME = 0x0005;

    /**
     * @var int
     */
    const TYPE_SOA = 0x0006;

    /**
     * @var int
     */
    const TYPE_PTR = 0x000C;

    /**
     * @var int
     */
    const TYPE_MX = 0x000F;

    /**
     * @var int
     */
    const TYPE_SRV = 0x0021;

    /**
     * @var int
     */
    const TYPE_AAAA = 0x001C;

    /**
     * @var int
     */
    const TYPE_ANY = 0x00FF;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $ttl;

    public function __construct(string $name, int $ttl)
    {
        $this->name = $name;
        $this->ttl = $ttl;
    }

    abstract public function getType(): int;

    public function getName(): string
    {
        return $this->name;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * Return whether the specified value is a supported Resource Record type
     * in this library.
     *
     * @param int $type
     * @return bool
     */
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
