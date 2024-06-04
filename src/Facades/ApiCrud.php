<?php

namespace Laraflow\ApiCrud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * // Crud Service Method Point Do not Remove //
 *
 * @see \Laraflow\ApiCrud\ApiCrud
 */
class ApiCrud extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Laraflow\ApiCrud\ApiCrud::class;
    }
}
