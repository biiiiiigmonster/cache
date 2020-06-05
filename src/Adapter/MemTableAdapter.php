<?php


namespace BiiiiiigMonster\Cache\Adapter;

use BiiiiiigMonster\Cache\Concern\AbstractAdapter;
use Swoole\Table;


class MemTableAdapter extends AbstractAdapter
{
    /**
     * @var Table
     */
    private Table $table;

    /**
     * @var int
     */
    private int $size = 10240;

    /**
     * Init instance.
     * Will called on swoft bean created.
     */
    public function init(): void
    {
        $this->table = new Table($this->size);
        $this->table->column(self::TIME_KEY,Table::TYPE_INT,8);
        $this->table->column(self::DATA_KEY,Table::TYPE_STRING,$this->size);
        $this->table->create();
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(Table::class);
    }

    /**
     * @param string $key
     * @param int $step
     * @return int
     */
    public function inc(string $key, int $step = 1): int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->table->incr($cacheKey,self::DATA_KEY,$step);
    }

    /**
     * @param string $key
     * @param int $step
     * @return int
     */
    public function dec(string $key, int $step = 1): int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->table->decr($cacheKey,self::DATA_KEY,$step);
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);
        $ttl = -2;// key不存在的时候返回-2，  此规范参照phpredis ttl返回值描述
        if($row = $this->table->get($cacheKey)) {
            /**
             * 这里获取到key的存活时间，有一点还有待验证
             * 如果TIME_KEY有设置的时候那么应该是个时间戳，当此有效期过了之后，是要删除还是怎么处理呢？
             */
            $ttl = $row[self::TIME_KEY] < 0 ? $row[self::TIME_KEY] : max($row[self::TIME_KEY]-time(),0);
        }

        return $ttl;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->checkKey($key);
        $cacheKey = $this->getCacheKey($key);

        $row = $this->table->get($cacheKey);
        if($row===false) {
            return is_null($default) ? false : $default;
        }

        // Data expired
        if($row[self::TIME_KEY]>=0 && $row[self::TIME_KEY] < time()){
            $this->table->del($cacheKey);
            return is_null($default) ? false : $default;
        }

        $value = $row[self::DATA_KEY];
        if(is_string($value)){
            $value = $this->getSerializer()->unserialize($value);
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null|int|\DateInterval $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);
        $ttl   = $this->formatTTL($ttl);

        // if value type not int&float, serialize it!
        if(!is_int($value) && !is_float($value)){
            $value = $this->getSerializer()->serialize($value);
        }

        return $this->table->set($cacheKey,[
            self::TIME_KEY => $ttl >= 0 ? $ttl + time() : -1,//not timeout value is -1
            self::DATA_KEY => $value
        ]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key): bool
    {
        $this->checkKey($key);

        $cacheKey = $this->getCacheKey($key);

        return $this->table->del($cacheKey);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->get($key) !== false;
    }
}
