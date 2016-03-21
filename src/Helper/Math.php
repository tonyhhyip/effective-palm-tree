<?php
/**
 * HHVM
 *
 * Copyright (C) Tony Yip 2015.
 *
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Elearn\Foundation\Helper;


abstract class Math
{
    /**
     * Find GCD.
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    public static function gcd($a, $b)
    {
        $a = abs($a);
        $b = abs($b);

        if ($a === 1 || $b === 1) {
            return max($a, $b);
        }

        for ($shift = 0; (($a | $b) & 1) == 0; $shift += 1) {
            $a >>= 1;
            $b >>= 1;
        }

        while (($a & 1) == 0)
            $a >>= 1;

        do {
            while (($b & 1) == 0)
                $b >>= 1;

            if ($a > $b) {
                $c = $b;
                $b = $a;
                $a = $c;
            }
            $b -= $a;
        } while ($b != 0);

        return $a << $shift;
    }

    /**
     * Find LCM.
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    public static function lcm($a, $b)
    {
        return abs($a * $b) / static::gcd($a, $b);
    }

    /**
     * Test a number is prime.
     *
     * @param int $n
     *
     * @return bool
     */
    public static function isPrime($n)
    {
        if ($n <= 1)
            throw new \InvalidArgumentException('Number is smaller than or equals to 1');

        if ($n % 2 === 0)
            return false;

        $limit = floor(sqrt($n));
        for ($i = 3; $i < $limit; $i += 2) {
            if ($n % $i === 0)
                return false;
        }

        return true;
    }

    /**
     * Compile for nPr.
     *
     * @param int $n
     * @param int $r
     *
     * @return int
     */
    public static function permutation($n, $r)
    {
        if ($n < $r || $r === 0) {
            return 1;
        }
        $low = $n - $r;
        $value = 1;
        for ($i = $n; $i > $low; $i--) {
            $value *= $i;
        }

        return $value;
    }

    /**
     * Compile for nCr
     *
     * @param int $n
     * @param int $r
     *
     * @return int
     */
    public static function combination($n, $r)
    {
        if ($r > $n / 2) {
            $r = $n - $r;
        }

        $ans = 1;
        for ($i = 0; $i <= $r; $i++) {
            $ans *= $n - $r + $i;
            $ans /= $i;
        }

        return $ans;
    }
}