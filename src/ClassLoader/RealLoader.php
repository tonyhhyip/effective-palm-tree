<?hh
/**
 * Laravel Rich
 *
 * Copyright (C) Tony Yip 2016.
 *
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software
 * and associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS",
 * WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category Laravel EnRich
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/MIT MIT License
 */

use Laravel\Rich\ClassLoader\ClassLoaderInterface;
use Laravel\Rich\ClassLoader\ComposerClassLoader;
use Laravel\Rich\ClassLoader\ApcClassLoader;

class RealLoader
{
    private static ?ClassLoaderInterface $loader = null;
    
    public static function loadClassLoader(string $class)
    {
        if (strpos($class, 'Laravel\\Rich\\ClassLoader\\') === 0) {
            require __DIR__ . '/' . str_replace('Elearn\\Foundation\\ClassLoader\\', '', $class) . '.php';
        }
    }
    
    private static function loadFile(string $fileIdentifier, string $file): void
    {
        if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
    }
    
    public static function getLoader(string $dir): ClassLoaderInterface
    {
        if (null !== self::$loader) {
            return self::$loader;
        }
        
        spl_autoload_register([RealLoader::class, 'loadClassLoader'], true, true);
        $loader = new ComposerClassLoader();
        
        $map = require($dir . '/autoload_namespaces.php');
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }
        
        $map = require $dir . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        $classMap = require $dir . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }
        
        $realLoader = new ApcClassLoader('elearn', $loader);
        
        $realLoader->register();
        
        $includeFiles = require $dir . '/autoload_files.php';
        foreach ($includeFiles as $fileIdentifier => $file) {
            self::loadFile($fileIdentifier, $file);
        }
    
        spl_autoload_unregister([RealLoader::class, 'loadClassLoader']);
        
        return self::$loader = $realLoader;
    }
}