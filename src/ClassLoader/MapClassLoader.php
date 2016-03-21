<?hh

/**
 * This is a file of elearn-foundation.
 *
 * (c) Tony Yip <tony@opensource.hk>
 * @license http://opensource.org/licenses/MIT MIT License.
 */

namespace Laravel\Rich\ClassLoader;


/**
 * The Symfony MapClassLoader in Hack Language to improve the performance.
 */
class MapClassLoader extends AbstractClassLoader
{

	private Map<string, string>$map;

	/**
	 * Constructor
	 * 
	 * @param array $map A map where keys are classes and values the absolute file path.
	 */
	public function __construct(array $map)
	{
		$this->map = new Map($map);
	}

	public function findFile(string $class): ?string
	{
		if ($this->map->containsKey($class)) {
			return $this->map->at($class);
		}
		return null;
	}
}