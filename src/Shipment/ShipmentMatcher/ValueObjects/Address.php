<?php

namespace Shipment\ShipmentMatcher\ValueObjects;

use Util\MathUtil;

class Address
{
    /**
     * @var int[]
     */
    private array $factors;
    public function __construct(private readonly int $id, private readonly string $address) {
        $this->factors = MathUtil::calculateFactors(strlen($this->address));
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
        return $this->address;
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