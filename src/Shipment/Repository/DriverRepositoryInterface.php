<?php

namespace Shipment\Repository;

use Shipment\Entities\Driver;

interface DriverRepositoryInterface
{
    /**
     * @return Driver[]
     */
    public function getDrivers(): array;
}
