<?php

namespace Shipment\Repository;

interface RepositoryRegistryInterface
{
    public function getDriverRepository(): DriverRepositoryInterface;
    public function getShipmentDestinationRepository(): ShipmentDestinationRepositoryInterface;
}
