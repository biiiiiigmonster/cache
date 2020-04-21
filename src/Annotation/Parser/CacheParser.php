<?php declare(strict_types=1);


namespace BiiiiiigMonster\Cache\Annotation\Parser;


use BiiiiiigMonster\Cache\Annotation\Mapping\Cache;
use BiiiiiigMonster\Cache\CacheRegister;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;

/**
 * Class CacheParser
 * @package BiiiiiigMonster\Cache\Annotation\Parser
 * @AnnotationParser(Cache::class)
 */
class CacheParser extends Parser
{
    /**
     * @param int $type
     * @param Cache $annotationObject
     * @return array
     * @throws AnnotationException
     */
    public function parse(int $type, $annotationObject): array
    {
        // TODO: Implement parse() method.
        if($type === self::TYPE_PROPERTY) {
            throw new AnnotationException('Annotation Cache should not on property!');
        }

        $data = [
            $annotationObject->getPrefix(),
            $annotationObject->getKey(),
            $annotationObject->getTtl(),
        ];

        CacheRegister::register($data, $this->className, $this->methodName);

        return [];
    }
}
