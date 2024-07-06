<?php

namespace Laraflow\Crud\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("crud.templates.{$value}"));
    }
}
