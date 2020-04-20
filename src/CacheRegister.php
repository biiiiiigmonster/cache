<?php


namespace BiiiiiigMonster\Cache;


/**
 * Class CacheRegister
 * @package BiiiiiigMonster\Cache
 * @since 2.0
 */
class CacheRegister
{
    /**
     * @var array
     */
    private static $data = [];

    /**
     * 注册
     * @param array $data
     * @param string $className
     * @param string $method
     */
    public static function register(array $data,string $className,string $method): void
    {
        self::$data[$className][$method] = $data;
    }

    /**
     * @param string $className
     * @param string $method
     * @return array
     */
    public static function get(string $className, string $method): array
    {
        return self::$data[$className][$method] ?? [];
    }
}
