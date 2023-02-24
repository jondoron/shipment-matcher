<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\ValueObjects\ShipmentDestination;

interface ShipmentDestinationRepositoryInterface
{
    /**
     * @return ShipmentDestination[]
     */
    public function getShipmentDestinations(): array;
}