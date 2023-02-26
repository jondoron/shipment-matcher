<?php

namespace Shipment\Entities;

use Webmozart\Assert\Assert;

class Driver
{
    public function __construct(private readonly int $id, private readonly string $driver)
    {
        Assert::notWhitespaceOnly($driver);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->driver;
    }
}
