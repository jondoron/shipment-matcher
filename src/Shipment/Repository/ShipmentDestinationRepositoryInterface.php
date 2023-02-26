<?php

namespace Shipment\Repository;

use Shipment\Entities\ShipmentDestination;

interface ShipmentDestinationRepositoryInterface
{
    /**
     * @return ShipmentDestination[]
     */
    public function getShipmentDestinations(): array;
}
