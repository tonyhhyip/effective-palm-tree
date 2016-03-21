<?hh

/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Elearn\Foundation\ClassLoader;


/**
 * The Symfony ApcClassLoader in Hack Language to improve the performance.
 */
class ApcClassLoader extends AbstractClassLoader
{

	/**
	 * The APC namespace prefix to use.
	 * @var string
	 */
	private string $prefix;

	/**
	 * A class loader object that implements the findFile() method.
	 * @var ClassLoaderInterface
	 */
	protected ClassLoaderInterface $instance;

	/**
	 * Constructor
	 * 
	 * @param string                      $prefix    The APC namespace prefix to use.
	 * @param ClassLoaderInterface $instance  A class loader object that implements the findFile() method.
	 */
	public function __construct(string $prefix, ClassLoaderInterface $instance)
	{
		if (!extension_loaded('apc')) {
			throw new \RuntimeException('Unable to use ApcClassLoader as APC is not enabled.');
		}

		$this->prefix = $prefix;
		$this->instance = $instance;
	}

	/**
     * Finds a file by class name while caching lookups to APC.
     *
     * @param string $class A class name to resolve to file
     *
     * @return string|null
     */
    public function findFile(string $class): ?string
    {
        if (false === $file = apc_fetch($this->prefix.$class)) {
            apc_store($this->prefix.$class, $file = $this->instance->findFile($class));
        }

        return $file;
    }
}