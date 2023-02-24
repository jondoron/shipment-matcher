<?php

namespace Shipment\ShipmentMatcher\Matcher;

use Shipment\ShipmentMatcher\Matcher\Strategy\ShipmentMatcherStrategyInterface;

class ShipmentMatcher
{

    public function __construct(private readonly ShipmentMatcherStrategyInterface $shipmentMatcherStrategy) {}

    public function getMatches(): array
    {
        $this->shipmentMatcherStrategy->loadData();
        $this->shipmentMatcherStrategy->generateMatches();
        return $this->shipmentMatcherStrategy->getResults();
    }

    public function getTotalScore(): float
    {
        return $this->shipmentMatcherStrategy->getResultScore();
    }
}
