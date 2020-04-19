<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type SOA.
 */
class SoaResourceRecord extends ResourceRecord
{
    /**
     * @var string
     */
    protected $nameserver;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $serialNumber;

    /**
     * @var int
     */
    protected $refresh;

    /**
     * @var int
     */
    protected $retry;

    /**
     * @var int
     */
    protected $expiry;

    /**
     * @var int
     */
    protected $negativeCachingTime;

    public function __construct(
        string $name,
        int $ttl,
        string $nameserver,
        string $email,
        int $serialNumber,
        int $refresh,
        int $retry,
        int $expiry,
        int $negativeCachingTime
    ) {
        parent::__construct($name, $ttl);
        $this->nameserver = $nameserver;
        $this->email = $email;
        $this->serialNumber = $serialNumber;
        $this->refresh = $refresh;
        $this->retry = $retry;
        $this->expiry = $expiry;
        $this->negativeCachingTime = $negativeCachingTime;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_SOA;
    }

    public function __toString(): string
    {
        return "{$this->nameserver}"
            . " {$this->email}"
            . " {$this->serialNumber}"
            . " {$this->refresh}"
            . " {$this->retry}"
            . " {$this->expiry}"
            . " {$this->negativeCachingTime}";
    }
}
