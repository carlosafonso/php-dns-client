<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type AAAA.
 */
class AaaaResourceRecord extends ResourceRecord
{
    /**
     * @var string
     */
    protected $address;

    public function __construct(string $name, int $ttl, string $address)
    {
        parent::__construct($name, $ttl);
        $this->address = $address;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_AAAA;
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
