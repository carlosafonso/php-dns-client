<?php

declare(strict_types=1);

namespace Afonso\Dns;

class Response
{
    const RCODE_NO_ERROR = 0x00;
    const RCODE_FORMAT_ERROR = 0x01;
    const RCODE_SERVER_FAILURE = 0x02;
    const RCODE_NAME_ERROR = 0x03;
    const RCODE_NOT_IMPLEMENTED = 0x04;
    const RCODE_REFUSED = 0x05;

    protected $isAuthoritative;

    protected $isRecursionAvailable;

    protected $type;

    protected $records;

    public function isAuthoritative(): bool
    {
        return $this->isAuthoritative;
    }

    public function setAuthoritative(bool $isAuthoritative): void
    {
        $this->isAuthoritative = $isAuthoritative;
    }

    public function isRecursionAvailable(): bool
    {
        return $this->isRecursionAvailable;
    }

    public function setRecursionAvailable(bool $isRecursionAvailable): void
    {
        $this->isRecursionAvailable = $isRecursionAvailable;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        if (!$this->isValidResponseType($type)) {
            throw new \InvalidArgumentException("'${type}' is not a valid DNS response type");
        }
        $this->type = $type;
    }

    public function getResourceRecords(): array
    {
        return $this->records;
    }

    public function addResourceRecord(ResourceRecordInterface $record): void
    {
        $this->records[] = $record;
    }

    private function isValidResponseType(int $type): bool
    {
        $validTypes = [
            self::RCODE_NO_ERROR,
            self::RCODE_FORMAT_ERROR,
            self::RCODE_SERVER_FAILURE,
            self::RCODE_NAME_ERROR,
            self::RCODE_NOT_IMPLEMENTED,
            self::RCODE_REFUSED
        ];
        return in_array($type, $validTypes);
    }
}
