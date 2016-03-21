<?hh

/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Elearn\Foundation\ClassLoader;

/**
 * Abstract Class of ClassLoader.
 */
abstract class AbstractClassLoader implements ClassLoaderInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function register(bool $prepend = false): void
	{
		spl_autoload_register([$this, 'loadClass'], true, $prepend);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unregister(): void
	{
		spl_autoload_unregister([$this, 'loadClass']);
	}

	/**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     * @return bool True, if loaded
     */
    public function loadClass(string $class): bool
    {
        if ($file = $this->findFile($class)) {
            require_once $file;

            return true;
        }

        return false;
    }

    abstract public function findFile(string $class): ?string;
}