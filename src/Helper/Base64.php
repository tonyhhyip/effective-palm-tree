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


abstract class Base64
{
    /**
     * @param string $data Base64 string
     * @param bool $strict input does not contains character from outside the base64 alphabet.
     *
     * @return string Decoded String.
     */
    public static function parse($data, $strict = false)
    {
        return base64_decode($data, $strict);
    }

    /**
     * @param string $data Data to be encoded.
     *
     * @return string Base64 String.
     */
    public static function encode($data)
    {
        return base64_encode($data);
    }
}