<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache\Aspect;


use BiiiiiigMonster\Cache\Annotation\Mapping\Cache as CacheMapping;
use BiiiiiigMonster\Cache\CacheManager;
use BiiiiiigMonster\Cache\CacheRegister;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class CacheAspect
 * @package BiiiiiigMonster\Cache\Aspect
 * @Aspect()
 * @PointAnnotation(
 *     include={CacheMapping::class}
 * )
 */
class CacheAspect
{
    /**
     * @Inject()
     *
     * @var CacheManager
     */
    private CacheManager $cache;
    /**
     * 环绕通知
     *
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     */
    public function aroundAdvice(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $className = $proceedingJoinPoint->getClassName();
        $method = $proceedingJoinPoint->getMethod();
        $argsMap = $proceedingJoinPoint->getArgsMap();

        [$key, $ttl] = CacheRegister::get($className,$method);
        if(!$key = $this->cache->evaluateKey($key,$className,$method,$argsMap)) {
            //如果没有从缓存注解中解析出有效key（因为CacheWrap注解key非必填），则采用默认规则来赋值key
            $key = "$className@$method";
        }

        return $this->cache->remember($key,fn() => $proceedingJoinPoint->proceed(),(int)$ttl);
    }
}
