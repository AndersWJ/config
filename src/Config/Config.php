<?php

namespace Awj\Config;

use RuntimeException;
use Awj\Config\Contract\ConfigReader;

class Config
{
    /**
     * Contains a initialized configReader
     *
     * @var ConfigReader $configReader
     */
    protected static $configReader;

    /**
     * Reads the key to determine the type of config reader to use.
     *
     * @param string $key
     * @param null $default
     * @param string $delimiter
     *
     * @return mixed
     */
    public static function read($key, $default = null, $delimiter = '.')
    {
        if (self::$configReader == null) {
            throw new RuntimeException('No config reader has been initialized');
        }

        return self::$configReader->get($key, $default, $delimiter);
    }

    /**
     * Initializes a config reader
     *
     * @param ConfigReader $reader
     */
    public static function initialize(ConfigReader $reader)
    {
        self::$configReader = $reader;
    }
}
