<?php

namespace Laraflow\ApiCrud\Exceptions;

use Exception;
use Laraflow\ApiCrud\Traits\ModelExceptionTrait;
use Throwable;

/**
 * Class StoreOperationException
 */
class StoreOperationException extends Exception
{
    use ModelExceptionTrait;

    protected $type = 'store';

    /**
     * StoreOperationException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        if (strlen($message) == 0) {
            $message = __('restapi::messages.exception.store', ['model' => $this->getModel()]);
        }

        parent::__construct($message, $code, $previous);
    }
}
