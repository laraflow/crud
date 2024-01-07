<?php

namespace Laraflow\Crud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * // Crud Service Method Point Do not Remove //
 *
 * @see \Laraflow\Crud\Generator
 */
class Generator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Laraflow\Crud\Generator::class;
    }
}
