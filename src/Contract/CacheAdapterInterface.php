<?php declare(strict_types=1);

namespace BiiiiiigMonster\Cache\Contract;


interface CacheAdapterInterface
{
    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string $key 缓存变量名
     * @param int    $step 步长
     * @return int
     */
    public function inc(string $key, int $step = 1) :int;

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $key 缓存变量名
     * @param int    $step 步长
     * @return int
     */
    public function dec(string $key, int $step = 1) :int;

    /**
     * 获取缓存有效时间
     * @access public
     * @param string $key 缓存变量名
     * @return int
     */
    public function ttl(string $key) :int;
}
