<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy;

use Shipment\ShipmentMatcher\Matcher\ShipmentMatcherResult;

interface ShipmentMatcherStrategyInterface
{
    public function loadData();

    public function generateMatches(): void;

    /**
     * @return ShipmentMatcherResult[]
     */
    public function getResults(): array;

    public function getSummary(): string;
}
