<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache;


use BiiiiiigMonster\Cache\Concern\AbstractAdapter;
use BiiiiiigMonster\Cache\Concern\CacheAbleTrait;
use BiiiiiigMonster\Cache\Contract\CacheAdapterInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class CacheManager
 * @package BiiiiiigMonster\Cache
 *
 * @method bool has($key)
 * @method bool set($key, $value, $ttl = null)
 * @method mixed get($key, $default = null)
 * @method bool delete($key)
 * @method bool clear()
 * @method array getMultiple($keys, $default = null)
 * @method bool setMultiple($values, $ttl = null)
 * @method bool deleteMultiple($keys)
 * @method int inc($key, $step = 1)
 * @method int dec($key, $step = 1)
 * @method int ttl($key)
 */
class CacheManager
{
    use CacheAbleTrait;
    /**
     * @var AbstractAdapter
     */
    protected AbstractAdapter $adapter;

    /**
     * @var ExpressionLanguage
     */
    protected ExpressionLanguage $el;

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        return $this->adapter->{$method}(...$arguments);
    }

    /**
     * @param string $key
     * @param string $className
     * @param string $method
     * @param array $args
     * @return string
     */
    public function evaluateKey(string $key, string $className, string $method, array $args): string
    {
        if($key==='') return "$className@$method";

        $values = array_merge($args,[
            'request' => context()->getRequest(),//表达式支持请求对象
            'CLASS' => $className,
            'METHOD' => $method,
        ]);
        return (string)$this->el->evaluate($key, $values);
    }

    /**
     * @return AbstractAdapter
     */
    public function getAdapter(): AbstractAdapter
    {
        return $this->adapter;
    }

    /**
     * @param AbstractAdapter $adapter
     */
    public function setAdapter(AbstractAdapter $adapter): void
    {
        $this->adapter = $adapter;
    }
}
