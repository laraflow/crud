<?php

namespace Laraflow\Crud\Exceptions;

use Exception;
use Laraflow\Crud\Traits\ModelExceptionTrait;
use Throwable;

/**
 * Class CreateOperationException
 */
class CreateOperationException extends Exception
{
    use ModelExceptionTrait;

    protected $type = 'store';

    /**
     * CreateOperationException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message = '', $code = 0, ?Throwable $previous = null)
    {
        if (strlen($message) == 0) {
            $message = __('restapi::messages.exception.store', ['model' => $this->getModel()]);
        }

        parent::__construct($message, $code, $previous);
    }
}
