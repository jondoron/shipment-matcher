<?php

namespace Shipment\ShipmentMatcher\Entities;

use Webmozart\Assert\Assert;

class ShipmentDestination
{
    public function __construct(private readonly int $id, private readonly string $streetName)
    {
        Assert::notWhitespaceOnly($this->streetName);
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
        return $this->streetName;
    }
}
