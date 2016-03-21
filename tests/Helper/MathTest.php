<?php
/**
 * HHVM
 *
 * Copyright (C) Tony Yip 2015.
 *
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Elearn\Foundation\Test\Helper;


use Elearn\Foundation\Helper\Math;

class MathTest extends \PHPUnit_Framework_TestCase
{
    public function testGCD()
    {
        $this->assertSame(1, Math::gcd(3, 4));
        $this->assertSame(5, Math::gcd(-15, 20));
        $this->assertSame(3, Math::gcd(1, 3));
    }
}
