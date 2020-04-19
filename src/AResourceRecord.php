<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type A.
 */
class AResourceRecord extends ResourceRecord
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
        return ResourceRecord::TYPE_A;
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
