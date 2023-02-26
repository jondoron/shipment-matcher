<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore;

use Shipment\ShipmentMatcher\Entities\Driver;

class DriverSuitabilityScore
{
    public function __construct(
        private readonly Driver $driver,
        private readonly float $score,
        private readonly ScoringMethodEnum $scoringMethod
    ) {
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

    /**
     * @return ScoringMethodEnum
     */
    public function getScoringMethod(): ScoringMethodEnum
    {
        return $this->scoringMethod;
    }
}
