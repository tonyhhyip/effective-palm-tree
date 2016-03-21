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


use Elearn\Foundation\Helper\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testDump()
    {
        $data = [
            'query' => [
                'match' => [
                    'account_number' => 20
                ]
            ]
        ];
        $this->assertSame('{"query":{"match":{"account_number":20}}}', Json::dump($data));
    }

    public function testParse()
    {
        $data = [
            "query" => [
                "match" => [
                    "address" => "mill"
                ]
            ]
        ];
        $output = Json::parse('{"query": { "match": { "address": "mill" } }}', true);
        $this->assertSame($data, $output);
    }
}
