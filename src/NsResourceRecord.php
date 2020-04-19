<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type NS.
 */
class NsResourceRecord extends ResourceRecord
{
    /**
     * @var string
     */
    protected $nameserver;

    public function __construct(string $name, int $ttl, string $nameserver)
    {
        parent::__construct($name, $ttl);
        $this->nameserver = $nameserver;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_NS;
    }

    public function __toString(): string
    {
        return $this->nameserver;
    }
}
