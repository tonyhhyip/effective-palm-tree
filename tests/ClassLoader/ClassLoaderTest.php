<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elearn\Foundation\Test\ClassLoader;

use Elearn\Foundation\ClassLoader\ClassLoader;

class ClassLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!defined('HHVM_VERSION')) {
            $this->markTestSkipped("Non HHVM");
        }
    }
    
    public function testGetPrefixes()
    {
        $this->setUp();
        $loader = new ClassLoader();
        $loader->addPrefix('Foo', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->addPrefix('Bar', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->addPrefix('Bas', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $prefixes = $loader->getPrefixes();
        $this->assertArrayHasKey('Foo', $prefixes);
        $this->assertArrayNotHasKey('Foo1', $prefixes);
        $this->assertArrayHasKey('Bar', $prefixes);
        $this->assertArrayHasKey('Bas', $prefixes);
    }

    /**
     * @dataProvider getLoadClassTests
     */
    public function testLoadClass($className, $testClassName, $message)
    {
        $loader = new ClassLoader();
        $loader->addPrefix('Namespaced2\\', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->addPrefix('Pearlike2_', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->loadClass($testClassName);
        $this->assertTrue(class_exists($className), $message);
    }

    public function getLoadClassTests()
    {
        return array(
            array('\\Namespaced2\\Foo', 'Namespaced2\\Foo',   '->loadClass() loads Namespaced2\Foo class'),
            array('\\Pearlike2_Foo',    'Pearlike2_Foo',      '->loadClass() loads Pearlike2_Foo class'),
        );
    }

    /**
     * @dataProvider getLoadNonexistentClassTests
     */
    public function testLoadNonexistentClass($className, $testClassName, $message)
    {
        $loader = new ClassLoader();
        $loader->addPrefix('Namespaced2\\', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->addPrefix('Pearlike2_', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->loadClass($testClassName);
        $this->assertFalse(class_exists($className), $message);
    }

    public function getLoadNonexistentClassTests()
    {
        return array(
            array('\\Pearlike3_Bar', '\\Pearlike3_Bar', '->loadClass() loads non existing Pearlike3_Bar class with a leading slash'),
        );
    }

    public function testAddPrefixSingle()
    {
        $loader = new ClassLoader();
        $loader->addPrefix('Foo', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $loader->addPrefix('Foo', __DIR__.DIRECTORY_SEPARATOR.'Fixtures');
        $prefixes = $loader->getPrefixes();
        $this->assertArrayHasKey('Foo', $prefixes);
        $this->assertCount(1, $prefixes['Foo']);
    }

    public function testAddPrefixesSingle()
    {
        $loader = new ClassLoader();
        $loader->addPrefixes(array('Foo' => array('foo', 'foo')));
        $loader->addPrefixes(array('Foo' => array('foo')));
        $prefixes = $loader->getPrefixes();
        $this->assertArrayHasKey('Foo', $prefixes);
        $this->assertCount(1, $prefixes['Foo'], print_r($prefixes, true));
    }

    public function testAddPrefixMulti()
    {
        $loader = new ClassLoader();
        $loader->addPrefix('Foo', 'foo');
        $loader->addPrefix('Foo', 'bar');
        $prefixes = $loader->getPrefixes();
        $this->assertArrayHasKey('Foo', $prefixes);
        $this->assertCount(2, $prefixes['Foo']);
        $this->assertContains('foo', $prefixes['Foo']);
        $this->assertContains('bar', $prefixes['Foo']);
    }
}
