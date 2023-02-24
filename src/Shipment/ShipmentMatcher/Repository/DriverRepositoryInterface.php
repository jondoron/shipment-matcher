<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\ValueObjects\Driver;

interface DriverRepositoryInterface
{
    /**
     * @return Driver[]
     */
    public function getDrivers(): array;
}