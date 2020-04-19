<?php

declare(strict_types=1);

namespace Afonso\Dns;

/**
 * This class represents a DNS Resource Record of type MX.
 */
class MxResourceRecord extends ResourceRecord
{
    /**
     * @var int
     */
    protected $preference;

    /**
     * @var string
     */
    protected $exchanger;

    public function __construct(
        string $name,
        int $ttl,
        int $preference,
        string $exchanger
    ) {
        parent::__construct($name, $ttl);
        $this->preference = $preference;
        $this->exchanger = $exchanger;
    }

    public function getType(): int
    {
        return ResourceRecord::TYPE_MX;
    }

    public function __toString(): string
    {
        return "{$this->preference} {$this->exchanger}";
    }
}
