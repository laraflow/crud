<?php

namespace Laraflow\Crud\Exceptions;

use Exception;
use Laraflow\Crud\Traits\ModelExceptionTrait;

/**
 * Class DeleteOperationException
 */
class DeleteOperationException extends Exception
{
    use ModelExceptionTrait;

    protected $type = 'delete';
}
