<?php

namespace Shipment\ShipmentMatcher\Entities;

class ShipmentDestination
{
    public function __construct(private readonly int $id, private readonly string $streetName)
    {
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
