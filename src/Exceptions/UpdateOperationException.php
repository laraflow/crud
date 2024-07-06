<?php

namespace Laraflow\Crud\Exceptions;

use Exception;
use Laraflow\Crud\Traits\ModelExceptionTrait;
use Throwable;

/**
 * Class UpdateOperationException
 */
class UpdateOperationException extends Exception
{
    use ModelExceptionTrait;

    protected $type = 'update';

    /**
     * UpdateOperationException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message = '', $code = 0, ?Throwable $previous = null)
    {
        if (strlen($message) == 0) {
            $message = __('restapi::messages.exception.update', ['model' => $this->getModel(), 'id' => $this->getId()]);
        }

        parent::__construct($message, $code, $previous);
    }
}
