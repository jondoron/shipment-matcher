<?php

namespace Shipment\ShipmentMatcher\ValueObjects;

class ShipmentMatcherResult
{
    public function __construct(
        private readonly Address $address,
        private readonly Driver $driver,
        private readonly float $score
    ) {}

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }
}