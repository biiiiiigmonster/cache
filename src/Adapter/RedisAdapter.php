<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache\Adapter;


use BiiiiiigMonster\Cache\Concern\AbstractAdapter;
use Swoft\Redis\Pool;

/**
 * Class RedisAdapter
 * @package BiiiiiigMonster\Cache\Adapter
 * 注意：当缓存采用redis驱动的时候，序列化参数则为redis连接池配置中的序列化选项，就不用调用缓存中配置的序列化配置了
 * 意思是存取直接redis操作，无需像其他驱动一样存/取之前都要序列化/反序列化一下
 */
class RedisAdapter extends AbstractAdapter
{
    /**
     * @var Pool
     */
    private Pool $redis;

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(Pool::class);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        $value = $this->redis->get($cacheKey);
        if ($value === false && !is_null($default)) {
            return $default;
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);
        $ttl   = $this->formatTTL($ttl);

        return $this->redis->set($cacheKey,$value,$ttl);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key): bool
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->redis->del($cacheKey) === 1;
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return false;
    }

    /**
     * @param iterable|array $keys
     * @param null $default
     * @return array
     */
    public function getMultiple($keys, $default = null): array
    {
        $this->checkKeys($keys);

        $cacheKeys = $this->getCacheKeys($keys);

        return $this->redis->mget($cacheKeys);
    }

    /**
     * @param iterable|array $values
     * @param null|int $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $keys = array_keys($values);
        $this->checkKeys($keys);

        $cacheKeys = $this->getCacheKeys($keys);
        $ttl   = $this->formatTTL($ttl);

        return $this->redis->mset(array_combine($cacheKeys,$values),$ttl);
    }

    /**
     * @param iterable|array $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        $this->checkKeys($keys);

        $cacheKeys = $this->getCacheKeys($keys);

        return $this->redis->del(...$cacheKeys) === count($keys);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->redis->exists($cacheKey);
    }

    /**
     * @param string $key
     * @param int $step
     * @return int
     */
    public function inc(string $key, int $step = 1) :int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->redis->incrBy($cacheKey,$step);
    }

    /**
     * @param string $key
     * @param int $step
     * @return int
     */
    public function dec(string $key, int $step = 1) :int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->redis->decrBy($cacheKey,$step);
    }

    /**
     * @param string $key
     * @return int
     */
    public function ttl(string $key) :int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->redis->ttl($cacheKey);
    }
}
