<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Entities\ShipmentDestination;

class FileShipmentDestinationRepository extends AbstractFileRepository implements ShipmentDestinationRepositoryInterface
{
    public function getShipmentDestinations(): array
    {
        /** @var ShipmentDestination[] $shipmentDestinations */
        $shipmentDestinations = [];
        $fn = fopen($this->filePath, "r");

        while (! feof($fn)) {
            $streetName = trim(fgets($fn));
            if (0 === strlen($streetName)) {
                continue;
            }
            $shipmentDestinations[] = new ShipmentDestination(count($shipmentDestinations), $streetName);
        }
        fclose($fn);

        return $shipmentDestinations;
    }
}
