<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Models\ShipmentDestination;

interface ShipmentDestinationRepositoryInterface
{
    /**
     * @return ShipmentDestination[]
     */
    public function getShipmentDestinations(): array;
}