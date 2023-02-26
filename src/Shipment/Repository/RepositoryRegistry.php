<?php

namespace Shipment\Repository;

class RepositoryRegistry implements RepositoryRegistryInterface
{
    public function __construct(
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly ShipmentDestinationRepositoryInterface $shipmentDestinationRepository
    ) {
    }

    public function getDriverRepository(): DriverRepositoryInterface
    {
        return $this->driverRepository;
    }

    public function getShipmentDestinationRepository(): ShipmentDestinationRepositoryInterface
    {
        return $this->shipmentDestinationRepository;
    }
}
