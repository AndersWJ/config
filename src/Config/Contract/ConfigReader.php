<?php

namespace Awj\Config\Contract;

interface ConfigReader
{
    /**
     * Gets a value from a configuration
     *
     * @param string $key
     * @param mixed $default
     * @param string $delimiter
     *
     * @return mixed
     */
    public function get($key, $default = null, $delimiter = '.');
}
