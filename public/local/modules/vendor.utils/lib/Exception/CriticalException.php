<?php

namespace Vendor\Utils\Exception;

use Exception;

/**
 * Class CriticalException
 * @package Vendor\Utils\Exception
 */
class CriticalException extends Exception
{
    /**
     * CriticalException constructor.
     *
     * @param $message
     * @param string $code
     */
    public function __construct(
        $message,
        $code = 'CRITICAL_ERROR'
    ) {
        parent::__construct($code . ": " . $message);
    }
}