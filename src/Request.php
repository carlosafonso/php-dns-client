<?php

declare(strict_types=1);

namespace Afonso\Dns;

class Request
{
    protected $name;

    protected $type;

    public function __construct(string $name, int $type)
    {
        if (!ResourceRecord::isValidType($type)) {
            throw new \InvalidArgumentException("'${type}' is not a valid DNS resource record type");
        }

        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
