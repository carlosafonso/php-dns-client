<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type PTR.
 */
class PtrResourceRecord extends ResourceRecord
{
    /**
     * @var string
     */
    protected $targetName;

    public function __construct(string $name, int $ttl, string $targetName)
    {
        parent::__construct($name, $ttl);
        $this->targetName = $targetName;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_PTR;
    }

    public function __toString(): string
    {
        return $this->targetName;
    }
}
