<?php declare(strict_types=1);

namespace BiiiiiigMonster\Cache;

use BiiiiiigMonster\Cache\Adapter\RedisAdapter;
use Swoft\Helper\ComposerJSON;
use Swoft\Serialize\JsonSerializer;
use Swoft\Serialize\PhpSerializer;
use Swoft\SwoftComponent;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use function dirname;

/**
 * Class AutoLoader
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            Cache::MANAGER => [
                'class'   => CacheManager::class,
                'adapter' => bean(Cache::ADAPTER),
                'el' => bean('el'),
            ],
            'el' => [
                'class' => ExpressionLanguage::class,
            ],
            Cache::ADAPTER => [
                'class'      => RedisAdapter::class,
                'redis'      => bean('redis.pool'),
//                'prefix'     => 'biiiiiigmonster:cache:',
                'serializer' => bean(Cache::SERIALIZER),
                // 'dataFile'   => alias('@runtime/caches/cache.data'),
            ],
            Cache::SERIALIZER => [
                'class' => PhpSerializer::class
            ]
        ];
    }
}
