<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache;


use BiiiiiigMonster\Cache\Concern\AbstractAdapter;
use BiiiiiigMonster\Cache\Concern\CacheAbleTrait;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class CacheManager
 * @package BiiiiiigMonster\Cache
 */
class CacheManager
{
    use CacheAbleTrait;
    /**
     * @var AbstractAdapter
     */
    protected AbstractAdapter $adapter;

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
        if($key==='') return '';
        // Parse express language
        $el = new ExpressionLanguage();
        $values = array_merge($args,[
            'request' => context()->getRequest(),//表达式支持请求对象
            'CLASS' => $className,
            'METHOD' => $method,
        ]);
        return (string)$el->evaluate($key, $values);
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
