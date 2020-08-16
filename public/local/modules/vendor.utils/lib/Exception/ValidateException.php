<?php

namespace Vendor\Utils\Exception;

use Bitrix\Rest\RestException;
use CRestServer;
use Exception;

/**
 * Class ValidateException
 * @package Vendor\Utils\Exception
 */
class ValidateException extends Exception
{
    /**
     * ValidateException constructor.
     *
     * @param                 $message
     * @param string $code
     */
    public function __construct(
        $message,
        $code = 'VALIDATE_EXCEPTION'
    ) {
        parent::__construct($code . ": " . $message);
    }
}