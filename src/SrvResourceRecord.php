<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type SRV.
 */
class SrvResourceRecord extends ResourceRecord
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $target;

    public function __construct(
        string $name,
        int $ttl,
        int $priority,
        int $weight,
        int $port,
        string $target
    ) {
        parent::__construct($name, $ttl);
        $this->priority = $priority;
        $this->weight = $weight;
        $this->port = $port;
        $this->target = $target;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_SRV;
    }

    public function __toString(): string
    {
        return "{$this->priority}"
            . " {$this->weight}"
            . " {$this->port}"
            . " {$this->target}";
    }
}
