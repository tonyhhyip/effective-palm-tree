<?hh

use Elearn\Foundation\ClassLoader\ClassLoaderInterface;
use Elearn\Foundation\ClassLoader\ComposerClassLoader;
use Elearn\Foundation\ClassLoader\ApcClassLoader;

class RealLoader
{
    private static ?ClassLoaderInterface $loader = null;
    
    public static function loadClassLoader(string $class)
    {
        if (strpos($class, 'Elearn\\Foundation\\ClassLoader\\') === 0) {
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