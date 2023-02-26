<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Entities\Driver;

interface DriverRepositoryInterface
{
    /**
     * @return Driver[]
     */
    public function getDrivers(): array;
}