<?php

namespace Zer0\Drivers\Redis;

use RedisClient\RedisClient;
use Zer0\Drivers\Traits\QueryLog;

/**
 * Class ExtRedisDebug
 * @package Zer0\Drivers\Redis
 */
class ExtRedisDebug
{
    use QueryLog;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * ExtRedisDebug constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
        $this->queryLogging = true;
    }

    /**
     * @param $method
     * @param $args
     * @return
     */
    public function __call($method, $args)
    {
        try {
            $t0 = microtime(true);
            return $ret = $this->redis->$method(...$args);
        } finally {
            $this->queryLog[] = [
                'query' => strtoupper($method) . ' '
                    . substr(json_encode($args, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 1, -1),
                'time' => microtime(true) - $t0,
                'trace' => new \Exception,
            ];
        }
    }
}
