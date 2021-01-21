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
        $type = $config->type ?? 'standalone';
        return new class {
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
                $this->config;
            }

            /**
             * @param callable $cb (Redis)
             */
            public function each(callable $cb)
            {
                foreach ($config->cluster_nodes ?? [$config->server] as $node) {
                    $redis = new \Redis();
                    $split = explode(':', $node);
                    $server = $split[0];
                    $port = $split[1] ?? null;
                    $redis->connect($server, $port ?? $config->port ?? 6379, $config->timeout ?? 0);
                    if (($config->read_timeout ?? null) !== null) {
                        $redis->setOption(\Redis::OPT_READ_TIMEOUT, $config->read_timeout ?? $config->timeout ?? 0);
                    }
                    $cb($redis);
                }
            }
        };
    }
}
