<?php

namespace Zer0\Brokers;

use Zer0\Config\Interfaces\ConfigInterface;

/**
 * Class RedisAsync
 * @package Zer0\Brokers
 */
class RedisAsync extends Base
{
    /**
     * @var string
     */
    protected $broker = 'Redis';

    /**
     * @param ConfigInterface $config
     * @return \PHPDaemon\Clients\Redis\Pool
     */
    public function instantiate(ConfigInterface $config): \PHPDaemon\Clients\Redis\Pool
    {
        return \PHPDaemon\Clients\Redis\Pool::getInstance([
            'servers' => 'tcp://' . $config->server,
            'max-allowed-packet' => '2G',
        ]);
    }

    /**
     * @param string $name
     * @param bool $caching
     * @return \PHPDaemon\Clients\Redis\Pool
     */
    public function get(string $name = '', bool $caching = true): \PHPDaemon\Clients\Redis\Pool
    {
        return parent::get($name, $caching);
    }
}
