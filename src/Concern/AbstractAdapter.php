<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache\Concern;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;
use Traversable;
use BiiiiiigMonster\Cache\Contract\CacheAdapterInterface;
use Swoft\Cache\Exception\InvalidArgumentException;
use Swoft\Serialize\Contract\SerializerInterface;
use Swoft\Serialize\PhpSerializer;

abstract class AbstractAdapter implements CacheAdapterInterface,CacheInterface
{
    //我还不知道这两个常量有啥用
    public const TIME_KEY = 't';
    public const DATA_KEY = 'd';

    /**
     * @var string
     */
    protected string $prefix = 'biiiiiigmonster:cache:';

    /**
     * Data serializer
     *
     * @var SerializerInterface
     */
    private ?SerializerInterface $serializer;

    /**
     * 每一个驱动都需要实现一个自检方法
     * @return bool
     */
    abstract public static function isSupported(): bool;

    /**
     * @param $key
     */
    protected function checkKey($key): void
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('The cache key must be an string');
        }
    }

    /**
     * @param array|Traversable|mixed $keys
     */
    protected function checkKeys($keys)
    {
        if (!is_array($keys) && !$keys instanceof Traversable) {
            throw new InvalidArgumentException('The cache keys must be an string array');
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        return $this->prefix . $key;
    }

    /**
     * @param array|Traversable $keys
     * @return array
     */
    public function getCacheKeys($keys): array
    {
        return array_map(fn($key) => $this->prefix.$key,$keys);
    }

    /**
     * @param $ttl
     * @return int
     */
    protected function formatTTL($ttl): int
    {
        if (is_int($ttl)) {
            return max($ttl,0);
        }

        if ($ttl instanceof DateInterval) {
            return (int)DateTime::createFromFormat('U', 0)->add($ttl)->format('U');
        }

        $msgTpl = 'Expiration date must be an integer, a DateInterval or null, "%s" given';
        throw new InvalidArgumentException(sprintf($msgTpl, is_object($ttl) ? get_class($ttl) : gettype($ttl)));
    }

    /**
     * 不存在则写入缓存数据后返回
     * @param string $key
     * @param mixed $value 缓存数据，支持闭包传参
     * @param int $ttl 过期时间
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remember(string $key,$value,?int $ttl=null)
    {
        $cache = $this->get($key);
        if($cache !== null) {
            return $cache;
        }

        if($value instanceof \Closure) {
            $value = $value();
        }

        $this->set($key,$value,$ttl);

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function forever($key, $value): bool
    {
        return $this->set($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function pull($key)
    {
        return tap($this->get($key), fn()=>$this->delete($key));
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * bean初始化没有填的时候默认PhpSerializer
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer ?: new PhpSerializer();
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}
