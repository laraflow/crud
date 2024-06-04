<?php

namespace Laraflow\ApiCrud\Exceptions;

use Exception;
use Throwable;

/**
 * Class FileAlreadyExistException
 */
class FileAlreadyExistException extends Exception
{
    public function __construct($message = '', $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
