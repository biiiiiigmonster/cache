<?php


namespace BiiiiiigMonster\Cache\Contract;


use Psr\SimpleCache\CacheInterface;

interface CacheAdapterInterface extends CacheInterface
{
    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string $key 缓存变量名
     * @param int    $step 步长
     * @return false|int
     */
    public function inc(string $key, int $step = 1);

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $key 缓存变量名
     * @param int    $step 步长
     * @return false|int
     */
    public function dec(string $key, int $step = 1);

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $key 缓存变量名
     * @return false|int
     */
    public function ttl(string $key);
}
