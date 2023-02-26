<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Entities\ShipmentDestination;

interface ShipmentDestinationRepositoryInterface
{
    /**
     * @return ShipmentDestination[]
     */
    public function getShipmentDestinations(): array;
}