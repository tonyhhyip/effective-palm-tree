<?hh

/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Elearn\Foundation\ClassLoader;

/**
 * This is the basic interface of ClassLoader.
 */
interface ClassLoaderInterface
{
	/**
	 * Registers this instance as an autoloader.
	 * 
	 * @param bool $prepend Whether to prepend the autoloader or not
	 */
	public function register(bool $prepend = false): void;

	/**
	 * Unregisters this instance as an autoloader.
	 */
	public function unregister(): void;

	/**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     * @return bool True, if loaded
     */
	public function loadClass(string $class): bool;

	/**
     * Finds a file by class name while caching lookups to APC.
     *
     * @param string $class A class name to resolve to file
     *
     * @return string|null
     */
	public function findFile(string $class): ?string;
}