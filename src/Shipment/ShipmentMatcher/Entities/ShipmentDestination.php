<?php

namespace Shipment\ShipmentMatcher\Entities;

use Util\MathUtil;

class ShipmentDestination
{
    /**
     * @var int[]
     */
    private array $factors;

    public function __construct(private readonly int $id, private readonly string $streetName)
    {
        $this->factors = MathUtil::calculateFactors(strlen($this->streetName));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->streetName;
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
