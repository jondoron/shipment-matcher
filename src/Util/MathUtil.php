<?php

namespace Util;

class MathUtil
{
    /**
     * Calculates the factors for a number, not including 1
     *
     * @return int[]
     */
    public static function calculateFactors(int $number): array
    {
        $factors = [];
        for ($i = 2; $i < floor($number) ; $i++) {
            if (0 === $number % $i) {
                $factors[] = $i;
            }
        }
        return $factors;
    }

    public static function isEven(int $number): bool
    {
        return 0 === $number % 2;
    }
}
