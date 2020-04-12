<?php

declare(strict_types=1);

namespace Afonso\Dns;

class ResourceRecord
{
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
}
