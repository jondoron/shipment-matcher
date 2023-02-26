<?php

namespace Shipment\ShipmentMatcher\Entities;

use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\DriverSuitabilityScore;
use Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore\ScoringMethodEnum;
use Util\MathUtil;
use Util\StringUtil;
use Webmozart\Assert\Assert;

class Driver
{
    public const EVEN_MULTIPLIER = 1.5;
    public const ODD_MULTIPLIER = 1.0;

    public const FACTOR_MULTIPLIER = 1.5;

    private array $factors = [];


    /**
     * @var DriverSuitabilityScore[]
     */
    private array $driverSuitabilityScores = [];

    public function __construct(private readonly int $id, private readonly string $driver)
    {
        Assert::notWhitespaceOnly($driver);

        $this->factors = MathUtil::calculateFactors(strlen($driver));

        $oddScore = self::calculateOddScore($driver);
        $evenScore = self::calculateEvenScore($this->driver);

        $driverSuitabilityScores = [];
        $driverSuitabilityScores[] = new DriverSuitabilityScore($this, $evenScore, ScoringMethodEnum::EVEN);
        $driverSuitabilityScores[] = new DriverSuitabilityScore($this, $oddScore, ScoringMethodEnum::ODD);
        if (!empty($this->factors)) {
            $driverSuitabilityScores[] = new DriverSuitabilityScore($this, $oddScore * self::FACTOR_MULTIPLIER, ScoringMethodEnum::ODD_WITH_FACTOR);
            $driverSuitabilityScores[] = new DriverSuitabilityScore($this, $evenScore * self::FACTOR_MULTIPLIER, ScoringMethodEnum::EVEN_WITH_FACTOR);
        }
        $this->driverSuitabilityScores = $driverSuitabilityScores;
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
        return $this->driver;
    }

    /**
     * @return array
     */
    public function getFactors(): array
    {
        return $this->factors;
    }

    /**
     * @return DriverSuitabilityScore[]
     */
    public function getDriverSuitabilityScores(): array
    {
        return $this->driverSuitabilityScores;
    }

    /**
     * If the length of the shipment's destination street name is even, the base suitability
     * score (SS) is the number of vowels in the driver’s name multiplied by 1.5.
     */
    private static function calculateEvenScore(string $driver): float
    {
        $vowelsRemoved = StringUtil::removeVowelsAndSpaces($driver);
        return (strlen($driver) - strlen($vowelsRemoved)) * self::EVEN_MULTIPLIER;
    }

    /**
     * If the length of the shipment's destination street name is odd, the base SS is the
     * number of consonants in the driver’s name multiplied by 1.
     */
    private static function calculateOddScore(string $driver): float
    {
        $vowelsRemoved = StringUtil::removeVowelsAndSpaces($driver);
        return strlen($vowelsRemoved) * self::ODD_MULTIPLIER;
    }
}
