<?php

namespace Laraflow\ApiCrud\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("fintech.generators.paths.generator.{$value}"));
    }
}
