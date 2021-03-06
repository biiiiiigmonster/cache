<?php declare(strict_types=1);

namespace BiiiiiigMonster\Cache\Exception;

use RuntimeException;

/**
 * Class CacheException
 *
 * @since 2.0.7
 */
class CacheException extends RuntimeException implements \Psr\SimpleCache\CacheException
{

}
