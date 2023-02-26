<?php

namespace Shipment\Repository;

use Shipment\Entities\Driver;

class FileDriverRepository extends AbstractFileRepository implements DriverRepositoryInterface
{
    public function getDrivers(): array
    {
        $fn = fopen($this->filePath, "r");

        /** @var Driver[] $drivers */
        $drivers = [];
        while (! feof($fn)) {
            $driverLine = trim(fgets($fn));
            if (0 === strlen($driverLine)) {
                continue;
            }
            $drivers[] = new Driver(count($drivers), $driverLine);
        }

        fclose($fn);

        return $drivers;
    }
}
