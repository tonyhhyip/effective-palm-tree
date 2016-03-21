<?hh

/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Elearn\Foundation\ClassLoader;


/**
 * The Symfony Psr4ClassLoader in Hack Language to improve the performance.
 */
class Psr4ClassLoader extends AbstractClassLoader
{

	private Vector $prefixes;

	public function __construct()
	{
		$this->prefixes = new Vector([]);
	}

	public function addPrefix(string $prefix, string $baseDir): void
	{
		$prefix = trim($prefix, '\\') . '\\';
		$baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		$this->prefixes[] = [$prefix, $baseDir];
	}

	public function findFile(string $class): ?string
    {
        $class = ltrim($class, '\\');

        foreach ($this->prefixes as list($currentPrefix, $currentBaseDir)) {
            if (0 === strpos($class, $currentPrefix)) {
                $classWithoutPrefix = substr($class, strlen($currentPrefix));
                $file = $currentBaseDir . str_replace('\\', DIRECTORY_SEPARATOR, $classWithoutPrefix) . '.php';
                if (file_exists($file)) {
                    return $file;
                } else if (file_exists($file = preg_replace('/\.php$/', '.hh', $file))) {
                	return $file;
                }
            }
        }

        return null;
    }
}