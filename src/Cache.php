<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache;


use Swoft\Bean\Annotation\Mapping\Bean;
use BiiiiiigMonster\Cache\Contract\CacheAdapterInterface;

/**
 * Class Cache
 * @package BiiiiiigMonster\Cache
 * @Bean()
 *
 * @method static bool has($key)
 * @method static bool set($key, $value, $ttl = null)
 * @method static get($key, $default = null)
 * @method static delete($key)
 * @method static bool clear()
 * @method static array getMultiple($keys, $default = null)
 * @method static bool setMultiple($values, $ttl = null)
 * @method static bool deleteMultiple($keys)
 * @method static CacheAdapterInterface getAdapter()
 * @method static void setAdapter(CacheAdapterInterface $adapter)
 * @method static remember($key, $ttl, \Closure $callback)
 * @method static bool forever($key, $value)
 * @method static pull($key, $default = null)
 */
class Cache
{
    // Cache manager bean name
    public const MANAGER    = 'cache.manager';
    public const ADAPTER    = 'cache.adapter';
    public const SERIALIZER = 'cache.serializer';

    /**
     * @return CacheManager
     */
    public static function manager(): CacheManager
    {
        return \Swoft::getBean(self::MANAGER);
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $cacheManager = self::manager();
        return $cacheManager->{$method}(...$arguments);
    }
}
