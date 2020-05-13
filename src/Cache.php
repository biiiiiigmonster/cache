<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache;


use BiiiiiigMonster\Cache\Contract\CacheAdapterInterface;

/**
 * Class Cache
 * @package BiiiiiigMonster\Cache
 *
 * @method static bool has($key)
 * @method static bool set($key, $value, $ttl = null)
 * @method static mixed get($key, $default = null)
 * @method static bool delete($key)
 * @method static bool clear()
 * @method static array getMultiple($keys, $default = null)
 * @method static bool setMultiple($values, $ttl = null)
 * @method static bool deleteMultiple($keys)
 * @method static CacheAdapterInterface getAdapter()
 * @method static void setAdapter(CacheAdapterInterface $adapter)
 * @method static mixed remember($key, $ttl, \Closure $callback)
 * @method static bool forever($key, $value)
 * @method static mixed pull($key, $default = null)
 * @method static int inc($key, $step = 1)
 * @method static int dec($key, $step = 1)
 * @method static int ttl($key)
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
