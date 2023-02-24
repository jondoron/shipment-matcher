<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy;

use Shipment\ShipmentMatcher\Matcher\ShipmentMatcher;

interface ShipmentMatcherStrategyInterface
{
    public function loadData();

    public function generateMatches(): void;

    /**
     * @return ShipmentMatcher[]
     */
    public function getResults(): array;

    public function getResultScore(): float;
}