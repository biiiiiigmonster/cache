<?php


namespace BiiiiiigMonster\Cache;


use BiiiiiigMonster\Cache\Contract\CacheAdapterInterface;
use Psr\SimpleCache\CacheInterface;

class CacheManager implements CacheInterface
{
    /**
     * @var CacheAdapterInterface
     */
    protected $adapter;

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        // TODO: Implement get() method.
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = null): bool
    {
        // TODO: Implement set() method.
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        // TODO: Implement clear() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getMultiple($keys, $default = null): array
    {
        // TODO: Implement getMultiple() method.
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple($values, $ttl = null): bool
    {
        // TODO: Implement setMultiple() method.
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple($keys): bool
    {
        // TODO: Implement deleteMultiple() method.
    }

    /**
     * {@inheritDoc}
     */
    public function has($key): bool
    {
        // TODO: Implement has() method.
    }

    /**
     * @return CacheAdapterInterface
     */
    public function getAdapter(): CacheAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @param CacheAdapterInterface $adapter
     */
    public function setAdapter(CacheAdapterInterface $adapter): void
    {
        $this->adapter = $adapter;
    }
}
