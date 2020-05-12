<?php

namespace Zer0\Brokers;

use RedisClient\RedisClient;
use Zer0\Config\Interfaces\ConfigInterface;
use Zer0\Drivers\Redis\RedisDebug;
use Zer0\Drivers\Redis\Tracy\BarPanel;
use Zer0\Model\Exceptions\UnsupportedActionException;

/**
 * Class Redis
 * @package Zer0\Brokers
 */
class Redis extends Base
{
    /**
     * @param ConfigInterface $config
     * @return RedisClient
     * @throws UnsupportedActionException
     */
    public function instantiate(ConfigInterface $config)
    {
        $type = $config->type ?? 'standalone';
        if ($type === 'standalone') {
            $attrs = $config->toArray();
            unset($attrs['type']);
            if (!isset($attrs['server'])) {
                $attrs['server'] = '127.0.0.1';
            }
            if (strpos($attrs['server'], ':') === false)  {
                $attrs['server'] .= ':6379';
            }

            $redis = new RedisClient($attrs);
        } else {
            throw new UnsupportedActionException;
        }


        $tracy = $this->app->factory('Tracy');
        if ($tracy !== null) {
            $redis = new RedisDebug($redis);
            $tracy->addPanel(new BarPanel($redis));
            $this->app->factory('HTTP')->on('endRequest', function () use ($redis) {
                $redis->resetQueryLog();
            });
        }

        return $redis;
    }
}
