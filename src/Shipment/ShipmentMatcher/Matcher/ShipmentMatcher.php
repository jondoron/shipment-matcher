<?php

namespace Shipment\ShipmentMatcher\Matcher;

use Shipment\ShipmentMatcher\ValueObjects\Address;
use Shipment\ShipmentMatcher\ValueObjects\ShipmentMatcherResult;
use Shipment\ShipmentMatcher\ValueObjects\Driver;
use Shipment\ShipmentMatcher\ValueObjects\DriverSuitabilityScore;
use Shipment\ShipmentMatcher\ValueObjects\ScoringMethodEnum;
use SplPriorityQueue;
use Util\MathUtil;
use Webmozart\Assert\Assert;

class ShipmentMatcher
{
    private DriverPriorityQueue $driverPriorityQueue;
    private array $addressesIndexByFactor = [];
    private array $oddAddresses = [];
    private array $evenAddresses = [];

    /**
     * @param Driver[] $drivers
     * @param Address[] $addresses
     */
    public function __construct(
        private readonly array $drivers,
        private readonly array $addresses
    ) {
        Assert::eq(count($drivers), count($addresses), 'The number of drivers must equal the number of addresses');
        Assert::notEmpty($this->drivers, 'The number of drivers and addresses must be greater than 0');
    }

    private function initializePriorityQueue(): void
    {
        // enter each driver into the priority queue by EACH of their scores, and the scoring strategy used
        // in our current case, that would be an entry for each odd, even, factored score
        $priorityQueue = new DriverPriorityQueue();
        foreach ($this->drivers as $driver) {
            foreach ($driver->getDriverSuitabilityScores() as $suitabilityScore) {
                $priorityQueue->insert($suitabilityScore, $suitabilityScore->getScore());
            }
        }

        $priorityQueue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
        $priorityQueue->top();
        $this->driverPriorityQueue = $priorityQueue;
    }

    private function initializeAddressLookups(): void
    {
        // organize addresses for quick lookup by factor
        $addressesIndexByFactor = [];
        // organize address by even and odd
        $evenAddresses = [];
        $oddAddresses = [];

        foreach ($this->addresses as $address) {
            foreach ($address->getFactors() as $factor) {
                if (!key_exists($factor, $addressesIndexByFactor)) {
                    $addressesIndexByFactor[$factor] = [];
                }
                $addressesIndexByFactor[$factor][$address->getId()] = $address;
            }
            if (MathUtil::isEven(strlen($address))) {
                $evenAddresses[$address->getId()] = $address;
                continue;
            }
            $oddAddresses[$address->getId()] = $address;
        }
        $this->addressesIndexByFactor = $addressesIndexByFactor;

        // sort with non-factors first!
        $this->evenAddresses = $evenAddresses;
        $this->oddAddresses = $oddAddresses;
    }

    public function matchDriversToAddresses(): array
    {
        $this->initializePriorityQueue();
        $this->initializeAddressLookups();

        // to store our results
        $results = [];

        // keep trakc of drivers and addresses that have been matched
        $matchedDrivers = [];
        $matchedAddresses = [];

        // let's go through the suitability scores highest to lowest
        while ($this->driverPriorityQueue->valid()) {
            /** @var DriverSuitabilityScore $currentSuitabilityScore */
            $currentSuitabilityScore = $this->driverPriorityQueue->current();
            $this->driverPriorityQueue->next();

            // if we've already assigned this driver we can skip this iteration
            if (key_exists($currentSuitabilityScore->getDriver()->getId(), $matchedDrivers)) {
                continue;
            }

            // based off of the scoring method, find the address
            $scoringMethod = $currentSuitabilityScore->getScoringMethod();
            // if the scoring method includes a factor in it's computation, lets search through our pre-indexed array to find an appropriate match
            $matchedAddress = null;
            if (ScoringMethodEnum::EVEN_WITH_FACTOR === $scoringMethod || ScoringMethodEnum::ODD_WITH_FACTOR === $scoringMethod) {
                $driverFactors = $currentSuitabilityScore->getDriver()->getFactors();

                foreach ($driverFactors as $driverFactor) {
                    // no addresses share this factor
                    if (!key_exists($driverFactor, $this->addressesIndexByFactor)) {
                        continue;
                    }

                    foreach ($this->addressesIndexByFactor[$driverFactor] as $address) {
                        if (key_exists($address->getId(), $matchedAddresses)) {
                            continue;
                        }
                        if (ScoringMethodEnum::EVEN_WITH_FACTOR === $scoringMethod && !MathUtil::isEven(strlen($address))) {
                            continue;
                        }
                        if (ScoringMethodEnum::ODD_WITH_FACTOR === $scoringMethod && MathUtil::isEven(strlen($address))) {
                            continue;
                        }
                        $matchedAddress = $address;
                    }
                }
            }

            if (ScoringMethodEnum::EVEN === $scoringMethod) {
                foreach ($this->evenAddresses as $address) {
                    if (key_exists($address->getId(), $matchedAddresses)) {
                        continue;
                    }
                    $matchedAddress = $address;
                }
            }

            if (ScoringMethodEnum::ODD === $scoringMethod) {
                foreach ($this->oddAddresses as $address) {
                    if (key_exists($address->getId(), $matchedAddresses)) {
                        continue;
                    }
                    $matchedAddress = $address;
                }
            }

            if (null === $matchedAddress) {
                continue;
            }
            $driver = $currentSuitabilityScore->getDriver();
            $results[] = new ShipmentMatcherResult($matchedAddress, $driver, $currentSuitabilityScore->getScore());
            $matchedAddresses[$matchedAddress->getId()] = $matchedAddress;
            $matchedDrivers[$driver->getId()] = $driver;
        }

        return $results;
    }
}
