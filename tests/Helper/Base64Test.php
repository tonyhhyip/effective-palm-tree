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


use Elearn\Foundation\Helper\Base64;

class Base64Test extends \PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $this->assertSame('U2hhdGluIFB1aSBZaW5nIENvbGxlZ2U=', Base64::encode('Shatin Pui Ying College'));
    }

    public function testDecode()
    {
        $this->assertSame('Project WHJSLS', Base64::parse('UHJvamVjdCBXSEpTTFM='));
    }
}
