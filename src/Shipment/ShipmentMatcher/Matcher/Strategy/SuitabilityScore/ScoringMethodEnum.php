<?php

namespace Shipment\ShipmentMatcher\Matcher\Strategy\SuitabilityScore;

enum ScoringMethodEnum
{
    case ODD;
    case EVEN;
    case ODD_WITH_FACTOR;

    case EVEN_WITH_FACTOR;

    public function name(): string
    {
        return match ($this) {
            self::ODD => 'odd',
            self::EVEN => 'even',
            self::ODD_WITH_FACTOR => 'odd_with_factor',
            self::EVEN_WITH_FACTOR => 'even_with_factor'
        };
    }
}
