<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Models\Driver;

interface DriverRepositoryInterface
{
    /**
     * @return Driver[]
     */
    public function getDrivers(): array;
}