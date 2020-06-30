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
class ExtRedis extends Base
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
    public function instantiate (ConfigInterface $config)
    {
        $type = $config->type ?? 'standalone';
        if ($type === 'standalone') {
            $redis = new \Redis();
            $redis->connect($config->server ?? '127.0.0.1', $config->port ?? 6379, $config->timeout ?? 0);
            if (($config->read_timeout ?? null) !== null) {
                $redis->setOption(\Redis::OPT_READ_TIMEOUT, $config->read_timeout ?? $config->timeout ?? 0);
            }
            if ($config->database ?? false) {
                $redis->select($config->database);
            }
        }
        else {
            throw new UnsupportedActionException;
        }

        $tracy = $this->app->factory('Tracy');
        if ($tracy !== null) {
            $redis = new ExtRedisDebug($redis);
            $tracy->addPanel(new BarPanel($redis));
            $this->app->factory('HTTP')->on(
                'endRequest',
                function () use ($redis) {
                    $redis->resetQueryLog();
                }
            );
        }

        return $redis;
    }
}
