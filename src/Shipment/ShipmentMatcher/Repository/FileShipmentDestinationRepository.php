<?php

namespace Shipment\ShipmentMatcher\Repository;

use Shipment\ShipmentMatcher\Models\ShipmentDestination;

class FileShipmentDestinationRepository extends AbstractFileRepository implements ShipmentDestinationRepositoryInterface
{
    public function getShipmentDestinations(): array
    {
        /** @var ShipmentDestination[] $addresses */
        $addresses = [];
        $fn = fopen($this->filePath, "r");

        while (! feof($fn)) {
            $addressLine = trim(fgets($fn));
            if (0 === strlen($addressLine)) {
                continue;
            }
            $addresses[] = new ShipmentDestination(count($addresses), $addressLine);
        }
        fclose($fn);

        return $addresses;
    }
}