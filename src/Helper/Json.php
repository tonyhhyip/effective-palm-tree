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


abstract class Json
{
    public static function parseFile($file)
    {
        $content = File::get($file);
        return static::parse($content);
    }
    
    /**
     * Turn json string into resource.
     *
     * @param string $content The json string being parse.
     * @param bool $assoc return object if true else array.
     * @param int $depth specified recursion depth.
     * @param int $options Bitmask of JSON decode options.
     *
     * @return mixed
     */
    public static function parse($content, $assoc = true, $depth = 512, $options = 0)
    {
        if (preg_match('/\{\}/', preg_replace('/\s/', '', $content)))
            return [];
        return json_decode($content, $assoc, $depth, $options);
    }

    /**
     * Turn Resource into json content.
     *
     * @param mixed $value The value being dump.
     * @param int $options JSON Constants
     * @param int $depth maximum depth. Must be greater than zero.
     *
     * @return string
     */
    public static function dump($value, $options = 0, $depth = 512)
    {
        return json_encode($value, $options, $depth);
    }

    /**
     * @param mixed $value The value being dump.
     * @param string $file The file to store.
     */
    public static function dumpFile($value, $file)
    {
        $data = static::dump($value);
        File::put($file, $data);
    }
}