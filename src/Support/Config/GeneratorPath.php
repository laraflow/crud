<?php

namespace Laraflow\Crud\Support\Config;

class GeneratorPath
{
    private $path;

    private $generate;

    private $namespace;

    public function __construct($config)
    {
        if (is_array($config)) {
            $this->path = $config['path'];
            $this->generate = $config['generate'];
            $this->namespace = $config['namespace'] ?? self::convertPathToNamespace($config['path']);

            return;
        }
        $this->path = $config;
        $this->generate = (bool) $config;
        $this->namespace = $config;
    }

    public static function convertPathToNamespace($path)
    {
        return str_replace(['/', 'src/'], '\\', $path);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function generate(): bool
    {
        return $this->generate;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
