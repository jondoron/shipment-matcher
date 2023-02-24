<?php

namespace Shipment\ShipmentMatcher\Repository;

interface RepositoryRegistryInterface
{
    public function getDriverRepository(): DriverRepositoryInterface;
    public function getShipmentDestinationRegistry(): ShipmentDestinationRepositoryInterface;
}