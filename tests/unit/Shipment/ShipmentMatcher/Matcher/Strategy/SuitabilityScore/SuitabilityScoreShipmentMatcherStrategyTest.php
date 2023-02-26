<?php

namespace unit\Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore;

use Shipment\Entities\Driver;
use Shipment\Entities\ShipmentDestination;
use Shipment\Repository\DriverRepositoryInterface;
use Shipment\Repository\RepositoryRegistry;
use Shipment\Repository\RepositoryRegistryInterface;
use Shipment\Repository\ShipmentDestinationRepositoryInterface;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\SuitabilityScoreShipmentMatcherStrategy;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SuitabilityScoreShipmentMatcherStrategyTest extends TestCase
{
    /** @var SuitabilityScoreShipmentMatcherStrategy */
    private $target;

    /** @var RepositoryRegistryInterface */
    private $repositoryRegistry;

    private $shipmentDestinationRepository;

    private $driverRepository;
    protected function setUp(): void
    {
        $this->shipmentDestinationRepository = m::mock(ShipmentDestinationRepositoryInterface::class);
        $this->driverRepository = m::mock(DriverRepositoryInterface::class);
        $this->repositoryRegistry = m::mock(RepositoryRegistry::class, [
            'getShipmentDestinationRepository' => $this->shipmentDestinationRepository,
            'getDriverRepository' => $this->driverRepository,
        ]);
        $this->target = new SuitabilityScoreShipmentMatcherStrategy($this->repositoryRegistry);
    }

    public function testGenerateMatches(): void
    {
        $this->driverRepository
            ->shouldReceive('getDrivers')
            ->once()
            ->andReturn([
                new Driver(1, 'Albus Dumbledore'),
                new Driver(2, 'Harry Potter'),
            ])
        ;

        $this->shipmentDestinationRepository
            ->shouldReceive('getShipmentDestinations')
            ->andReturn([
                new ShipmentDestination(1, 'Evergreen Drive'),
                new ShipmentDestination(2, 'Highland Ave.'),
            ])
        ;

        $this->target->generateMatches();
        $results = $this->target->getResults();

        $this->assertCount(2, $results);

        // scores should be in decreasing order
        $this->assertGreaterThan($results[1]->getScore(), $results[0]->getScore());

        // Harry Potter expected to have the highest scoring assignment to Evergreen Drive
        $this->assertEquals(2, $results[0]->getDriver()->getId());
        $this->assertEquals('Harry Potter', $results[0]->getDriver());
        $this->assertEquals('Evergreen Drive', $results[0]->getShipmentDestination());

        // Then Dumbledore to Highland Ave
        $this->assertEquals(1, $results[1]->getDriver()->getId());
        $this->assertEquals('Albus Dumbledore', $results[1]->getDriver());
        $this->assertEquals('Highland Ave.', $results[1]->getShipmentDestination());
    }
}
