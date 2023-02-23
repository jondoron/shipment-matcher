<?php

namespace Shipment\ShipmentMatcher\ValueObjects;

enum ScoringMethodEnum
{
    case ODD;
    case EVEN;
    case ODD_WITH_FACTOR;

    case EVEN_WITH_FACTOR;

    public function name(): string
    {
        return match($this)
        {
            self::ODD => 'odd',
            self::EVEN => 'even',
            self::ODD_WITH_FACTOR => 'odd_factor',
            self::EVEN_WITH_FACTOR => 'even_factor'
        };
    }
}