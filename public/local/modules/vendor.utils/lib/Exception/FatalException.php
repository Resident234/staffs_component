<?php

namespace Vendor\Utils\Exception;

use Bitrix\Rest\RestException;
use CRestServer;
use Exception;

/**
 * Class FatalException
 * @package Vendor\Utils\Exception
 */
class FatalException extends RestException
{
    /**
     * FatalException constructor.
     *
     * @param                 $message
     * @param string          $code
     */
    public function __construct(
        $message,
        $code = 'FATAL_ERROR'
    ) {
        parent::__construct($code . ": " . $message);
    }
}