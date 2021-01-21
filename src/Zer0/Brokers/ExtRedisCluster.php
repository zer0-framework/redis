<?php

namespace Zer0\Brokers;

use RedisClient\RedisClient;
use Zer0\Config\Interfaces\ConfigInterface;
use Zer0\Drivers\Redis\ExtRedisDebug;
use Zer0\Drivers\Redis\Tracy\BarPanel;
use Zer0\Model\Exceptions\UnsupportedActionException;

/**
 * Class ExtRedis
 * @package Zer0\Brokers
 */
class ExtRedisCluster extends Base
{

    /**
     * @var string
     */
    protected $broker = 'Redis';

    /**
     * @param ConfigInterface $config
     *
     * @return \Redis
     * @throws UnsupportedActionException
     */
    public function instantiate(ConfigInterface $config)
    {
        return new class ($config) {
            /**
             * @var ConfigInterface
             */
            private $config;

            /**
             *  constructor.
             * @param ConfigInterface $config
             */
            public function __construct(ConfigInterface $config)
            {
                $this->config = $config;
            }

            /**
             * @param callable $cb (Redis)
             */
            public function each(callable $cb)
            {
                foreach ($this->config->luster_nodes ?? [$this->config->server] as $node) {
                    $redis = new \Redis();
                    $split = explode(':', $node);
                    $server = $split[0];
                    $port = $split[1] ?? null;
                    $redis->connect($server, $port ?? $this->config->port ?? 6379, $this->config->timeout ?? 0);
                    if (($this->config->read_timeout ?? null) !== null) {
                        $redis->setOption(\Redis::OPT_READ_TIMEOUT, $this->config->read_timeout ?? $this->config->timeout ?? 0);
                    }
                    $cb($redis);
                }
            }
        };
    }
}
