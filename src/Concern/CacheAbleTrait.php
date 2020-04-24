<?php

namespace BiiiiiigMonster\Cache\Concern;


trait CacheAbleTrait
{
    /**
     * 不存在则写入缓存数据后返回
     * @param string $key
     * @param mixed $value 缓存数据，支持闭包传参
     * @param int|\DateInterval $ttl 过期时间
     * @return mixed
     */
    public function remember(string $key,$value,$ttl = null)
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
}
