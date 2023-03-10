<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore;

use Shipment\Entities\Driver as EntityDriver;
use Shipment\Entities\ShipmentDestination as EntityShipmentDestination;
use Shipment\Repository\RepositoryRegistryInterface;
use Shipment\ShipmentMatcher\Matcher\ShipmentMatcherResult;
use Shipment\ShipmentMatcher\Matcher\Strategy\ShipmentMatcherStrategyInterface;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\Driver;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\DriverSuitabilityScore;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\ScoringMethodEnum;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models\ShipmentDestination;
use Webmozart\Assert\Assert;

class SuitabilityScoreShipmentMatcherStrategy implements ShipmentMatcherStrategyInterface
{
    /**
     * @var Driver[]
     */
    private array $drivers;

    /**
     * @var ShipmentDestination[]
     */
    private array $shipmentDestinations;

    /**
     * @var ShipmentMatcherResult[]
     */
    private array $matchResults = [];
    private float $totalScore = 0;

    public function __construct(private readonly RepositoryRegistryInterface $repositoryRegistry)
    {
    }

    private function loadData()
    {
        $this->drivers = array_map(
            static function (EntityDriver $driver) {
                return new Driver($driver);
            },
            $this->repositoryRegistry
            ->getDriverRepository()
            ->getDrivers()
        );
        $this->shipmentDestinations = array_map(
            static function (EntityShipmentDestination $shipmentDestination) {
                return new ShipmentDestination($shipmentDestination);
            },
            $this->repositoryRegistry
            ->getShipmentDestinationRepository()
            ->getShipmentDestinations()
        );
        Assert::eq(count($this->drivers), count($this->shipmentDestinations), 'The number of drivers must equal the number of shipment destinations');
        Assert::notEmpty($this->drivers, 'The number of drivers and shipment destinations must be greater than 0');
    }

    /**
     * See the README.md file in this directory for implementation notes
     */
    public function generateMatches(): void
    {
        $this->loadData();

        // organize shipment destinatinos for quick lookup by factor
        $shipmentDestinationsIndexedByFactor = [];
        // organize shipment destinations by even and odd
        $evenShipmentDestinations = [];
        $oddShipmentDestinations = [];

        foreach ($this->shipmentDestinations as $shipmentDestination) {
            foreach ($shipmentDestination->getFactors() as $factor) {
                if (!key_exists($factor, $shipmentDestinationsIndexedByFactor)) {
                    $shipmentDestinationsIndexedByFactor[$factor] = [];
                }
                $shipmentDestinationsIndexedByFactor[$factor][$shipmentDestination->getId()] = $shipmentDestination;
            }
            if ($shipmentDestination->isEvenLength()) {
                $evenShipmentDestinations[$shipmentDestination->getId()] = $shipmentDestination;
                continue;
            }
            $oddShipmentDestinations[$shipmentDestination->getId()] = $shipmentDestination;
        }

        // keep track of drivers and shipment destinations that have been matched
        $matchedDrivers = [];
        $matchedShipmentDestinations = [];

        $priorityQueue = $this->createPriorityQueue();

        // let's go through the suitability scores highest to lowest
        while ($priorityQueue->valid()) {
            /** @var DriverSuitabilityScore $currentSuitabilityScore */
            $currentSuitabilityScore = $priorityQueue->current();
            $priorityQueue->next();

            // if we've already assigned this driver we can skip this iteration
            if (key_exists($currentSuitabilityScore->getDriver()->getId(), $matchedDrivers)) {
                continue;
            }

            // based off of the scoring method, find the address
            $scoringMethod = $currentSuitabilityScore->getScoringMethod();
            // if the scoring method includes a factor in its computation, we search through our pre-indexed array to find an appropriate match
            $matchedShipmentDestination = null;
            if (ScoringMethodEnum::EVEN_WITH_FACTOR === $scoringMethod || ScoringMethodEnum::ODD_WITH_FACTOR === $scoringMethod) {
                $driverFactors = $currentSuitabilityScore->getDriver()->getFactors();

                foreach ($driverFactors as $driverFactor) {
                    // no addresses share this factor
                    if (!key_exists($driverFactor, $shipmentDestinationsIndexedByFactor)) {
                        continue;
                    }

                    foreach ($shipmentDestinationsIndexedByFactor[$driverFactor] as $shipmentDestination) {
                        if (key_exists($shipmentDestination->getId(), $matchedShipmentDestinations)) {
                            continue;
                        }
                        if (ScoringMethodEnum::EVEN_WITH_FACTOR === $scoringMethod && !$shipmentDestination->isEvenLength()) {
                            continue;
                        }
                        if (ScoringMethodEnum::ODD_WITH_FACTOR === $scoringMethod && $shipmentDestination->isEvenLength()) {
                            continue;
                        }
                        $matchedShipmentDestination = $shipmentDestination;
                    }
                }
            }

            // if the scoring method is just EVEN, search through our pre-filtered evens array
            if (ScoringMethodEnum::EVEN === $scoringMethod) {
                foreach ($evenShipmentDestinations as $shipmentDestination) {
                    if (key_exists($shipmentDestination->getId(), $matchedShipmentDestinations)) {
                        continue;
                    }
                    $matchedShipmentDestination = $shipmentDestination;
                }
            }

            // if the scoring method is just EVEN, search through our pre-filtered evens array
            if (ScoringMethodEnum::ODD === $scoringMethod) {
                foreach ($oddShipmentDestinations as $shipmentDestination) {
                    if (key_exists($shipmentDestination->getId(), $matchedShipmentDestinations)) {
                        continue;
                    }
                    $matchedShipmentDestination = $shipmentDestination;
                }
            }

            if (null === $matchedShipmentDestination) {
                continue;
            }

            $this->totalScore += $currentSuitabilityScore->getScore();
            $driver = $currentSuitabilityScore->getDriver();
            $this->matchResults[] = new ShipmentMatcherResult($matchedShipmentDestination, $driver, $currentSuitabilityScore->getScore());
            $matchedShipmentDestinations[$matchedShipmentDestination->getId()] = $matchedShipmentDestination;
            $matchedDrivers[$driver->getId()] = $driver;
        }
    }

    /**
     * @return ShipmentMatcherResult[]
     */
    public function getResults(): array
    {
        return $this->matchResults;
    }

    private function createPriorityQueue(): SuitabilityScorePriorityQueue
    {
        // enter each driver into the priority queue by EACH of their scores, and the scoring strategy used
        // in our current case, that would be an entry for each odd, even, factored score
        $priorityQueue = new SuitabilityScorePriorityQueue();
        foreach ($this->drivers as $driver) {
            foreach ($driver->getDriverSuitabilityScores() as $suitabilityScore) {
                $priorityQueue->insert($suitabilityScore, $suitabilityScore->getScore());
            }
        }

        $priorityQueue->setExtractFlags(\SplPriorityQueue::EXTR_DATA);
        $priorityQueue->top();
        return $priorityQueue;
    }

    public function getSummary(): string
    {
        return sprintf(
            "Total suitability score: %s",
            number_format($this->totalScore, 2)
        );
    }
}
