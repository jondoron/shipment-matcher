<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\Models;

use Shipment\ShipmentMatcher\Entities\ShipmentDestination as ShipmentDestinationEntity;
use Util\MathUtil;

class ShipmentDestination
{
    /**
     * @var int[]
     */
    private array $factors;

    public function __construct(private readonly ShipmentDestinationEntity $inner)
    {
        $this->factors = MathUtil::calculateFactors(strlen($this->inner));
    }

    public function getId(): int
    {
        return $this->inner->getId();
    }

    public function __toString(): string
    {
        return (string) $this->inner;
    }

    /**
     * @return int[]
     */
    public function getFactors(): array
    {
        return $this->factors;
    }

    public function isEven(): bool
    {
        return MathUtil::isEven(strlen($this));
    }
}