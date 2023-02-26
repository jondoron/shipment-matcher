<?php

namespace Shipment\ShipmentMatcher\Matcher;

use Shipment\ShipmentMatcher\Matcher\Strategy\ShipmentMatcherStrategyInterface;

class ShipmentMatcher
{
    public function __construct(private readonly ShipmentMatcherStrategyInterface $shipmentMatcherStrategy)
    {
    }

    public function generateMatches(): void
    {
        $this->shipmentMatcherStrategy->generateMatches();
    }

    public function getResults(): array
    {
        return $this->shipmentMatcherStrategy->getResults();
    }

    public function getSummary(): string
    {
        return $this->shipmentMatcherStrategy->getSummary();
    }
}
