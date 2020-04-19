<?php

declare(strict_types=1);

namespace Afonso\Dns;

interface ResourceRecordInterface
{
    /**
     * Return the name that this Resource Record refers to.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return the type identifier ofthis Resource Record.
     *
     * @return string
     */
    public function getType(): int;

    /**
     * Return the time-to-live (TTL), in seconds, for this Resource Record.
     *
     * @return int
     */
    public function getTtl(): int;

    public function __toString(): string;
}
