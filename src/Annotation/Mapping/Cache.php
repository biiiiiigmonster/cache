<?php


namespace BiiiiiigMonster\Cache\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Cache
 * @package BiiiiiigMonster\Cache\Annotation\Mapping
 * @Annotation()
 * @Target({"METHOD","CLASS"})
 * @Attributes(
 *     @Attribute("key",type="string"),
 *     @Attribute("ttl",type="int")
 * )
 */
class Cache
{
    /**
     * @var string
     */
    private $prefix = 'biiiiiigmonster:cache:';
    /**
     * 注解key支持symfony/expression-language语法表达式
     * @var string
     */
    private $key = '';

    /**
     * @var int
     */
    private $ttl = 3600;

    /**
     * CacheWrap constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['key'])) {
            $this->key = $values['key'];
        }
        if (isset($values['ttl'])) {
            $this->ttl = (int)$values['ttl'];
        }
        if (isset($values['prefix'])) {
            $this->prefix = $values['prefix'];
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }
}
