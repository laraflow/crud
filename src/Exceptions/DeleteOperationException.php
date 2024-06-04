<?php

namespace Laraflow\ApiCrud\Exceptions;

use Exception;
use Laraflow\ApiCrud\Traits\ModelExceptionTrait;

/**
 * Class DeleteOperationException
 */
class DeleteOperationException extends Exception
{
    use ModelExceptionTrait;

    protected $type = 'delete';
}
