<?php

namespace unit\Shipment\ShipmentMatcher;

use Shipment\ShipmentMatcher\Matcher\ShipmentMatcher;
use PHPUnit\Framework\TestCase;
use Shipment\ShipmentMatcher\Matcher\Strategy\ShipmentMatcherStrategyInterface;
use Mockery as m;

class ShipmentMatcherTest extends TestCase
{
    /** @var ShipmentMatcher */
    private $target;

    /** @var ShipmentMatcherStrategyInterface */
    private $strategy;
    protected function setUp(): void
    {
        $this->strategy = m::mock(ShipmentMatcherStrategyInterface::class);
        $this->target = new ShipmentMatcher($this->strategy);
    }

    public function testGenerateMatches(): void
    {
        $this->strategy->shouldReceive('generateMatches')->once();
        $this->target->generateMatches();
        $this->expectNotToPerformAssertions();
    }

    public function testGetResults(): void
    {
        $this->strategy->shouldReceive('getResults')->once();
        $this->target->getResults();
        $this->expectNotToPerformAssertions();
    }

    public function testGetSummary(): void
    {
        $this->strategy->shouldReceive('getSummary')->once();
        $this->target->getSummary();
        $this->expectNotToPerformAssertions();
    }
}
