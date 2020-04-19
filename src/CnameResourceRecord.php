<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type CNAME.
 */
class CnameResourceRecord extends ResourceRecord
{
    /**
     * @var string
     */
    protected $canonicalName;

    public function __construct(string $name, int $ttl, string $canonicalName)
    {
        parent::__construct($name, $ttl);
        $this->canonicalName = $canonicalName;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_CNAME;
    }

    public function __toString(): string
    {
        return $this->canonicalName;
    }
}
