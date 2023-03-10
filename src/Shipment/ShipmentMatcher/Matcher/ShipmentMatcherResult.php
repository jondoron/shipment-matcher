<?php

namespace Shipment\ShipmentMatcher\Matcher;

use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\Driver;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\ShipmentDestination;

class ShipmentMatcherResult
{
    public function __construct(
        private readonly ShipmentDestination $address,
        private readonly Driver              $driver,
        private readonly float               $score
    ) {
    }

    /**
     * @return ShipmentDestination
     */
    public function getShipmentDestination(): ShipmentDestination
    {
        return $this->address;
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }
}
