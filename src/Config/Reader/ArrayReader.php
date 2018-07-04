<?php

namespace Awj\Config\Reader;

use InvalidArgumentException;
use Awj\Config\Contract\ConfigReader;

class ArrayReader implements ConfigReader
{

    /**
     * Contains all the configurations as an array
     *
     * @var array $configurations
     */
    protected $configurations = [];

    /**
     * ArrayReader constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            $message = sprintf('Path %s not found for configuration', $path);

            throw new InvalidArgumentException($message);
        }

        $this->loadConfigurationFiles($path);
    }

    /**
     * Gets a value from a configuration
     *
     * @param string $key
     * @param mixed $default
     * @param string $delimiter
     *
     * @return mixed
     */
    public function get($key, $default = null, $delimiter = '.')
    {
        if ($key == "" || !is_string($key)) {
            return $default;
        }

        if (strpos($key, $delimiter) === false) {
            return array_key_exists($key, $this->configurations)
                ? $this->configurations[$key]
                : $default;
        }

        $configurations = $this->configurations;
        $keys = explode($delimiter, $key);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $configurations)) {
                return $default;
            }

            $configurations = $configurations[$key];
        }

        return $configurations;
    }

    protected function loadConfigurationFiles($path, $extension = '.php')
    {
        $files = scandir($path);
        $files = array_filter($files, function ($file) use ($extension) {
            return (bool) strpos($file, $extension) !== false;
        });

        foreach ($files as $file) {
            $filename = str_replace($extension, '', $file);

            $this->configurations[$filename] = require $path . DIRECTORY_SEPARATOR . $file;
        }
    }
}
