<?php

namespace unit\Util;

use PHPUnit\Framework\TestCase;
use Util\MathUtil;

class MathUtilTest extends TestCase
{
    public function testIsEvenWithEvenNumbers(): void
    {
        $this->assertTrue(MathUtil::isEven(-100));
        $this->assertTrue(MathUtil::isEven(0));
        $this->assertTrue(MathUtil::isEven(2));
        $this->assertTrue(MathUtil::isEven(8));
        $this->assertTrue(MathUtil::isEven(150));
    }

    public function testIsEvenWithOddNumbers(): void
    {
        $this->assertFalse(MathUtil::isEven(-101));
        $this->assertFalse(MathUtil::isEven(-1));
        $this->assertFalse(MathUtil::isEven(5));
        $this->assertFalse(MathUtil::isEven(9));
        $this->assertFalse(MathUtil::isEven(199));
    }
}
