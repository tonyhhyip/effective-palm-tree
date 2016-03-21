<?hh
/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Elearn\Foundation\ClassLoader;


/**
 * The Symfony ClassLoader in Hack Language to improve the performance.
 */
class ClassLoader extends AbstractClassLoader
{
	private Map<string, Set<string>> $prefixes;

	public function __construct()
	{
		$this->prefixes = new Map([]);
	}

	public function getPrefixes(): array<string, Set>
	{
		return $this->prefixes->toArray();
	}

	public function addPrefixes(array $prefixes): void
	{
		foreach ($prefixes as $prefix => $path) {
			$this->addPrefix($prefix, $path);
		}
	}

	public function addPrefix(string $prefix, mixed $path)
	{
		if ($this->prefixes->containsKey($prefix)) {
			$this->prefixes[$prefix]->addAll((array)$path);
		} else {
			$this->prefixes[$prefix] = new Set((array)$path);
		}
	}

	public function findFile(string $class): ?string
	{
		if (false !== $pos = strpos($class, '\\')) {
			// namespaced class namespaced
			$classPath = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0, $pos)).DIRECTORY_SEPARATOR;
            $className = substr($class, $pos + 1);
		} else {
			// PEAR-like class name
            $classPath = null;
            $className = $class;
		}

		$classPath .= str_replace('_', DIRECTORY_SEPARATOR, $className);

		foreach ($this->prefixes as $prefix => $dirs) {
            if ($class === strstr($class, $prefix)) {
                foreach ($dirs as $dir) {
                	$classFile = $dir . DIRECTORY_SEPARATOR . $classPath;
                    if (file_exists($classFile . '.php')) {
                        return $dir . DIRECTORY_SEPARATOR . $classPath . '.php';
                    } elseif (file_exists($classFile . '.hh')) {
                    	return $dir . DIRECTORY_SEPARATOR . $classPath . '.hh';
                    }
                }
            }
        }

        return null;
	}

}