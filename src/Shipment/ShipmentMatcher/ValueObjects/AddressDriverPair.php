<?php

namespace Shipment\ShipmentMatcher\ValueObjects;

class AddressDriverPair
{
    public function __construct(private readonly Address $address, private readonly Driver $driver) {}

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


}