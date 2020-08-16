<?php

namespace Vendor\Utils\Exception;

use CRestServer;
use Exception;

/**
 * Class CriticalModuleException
 * @package Vendor\Utils\Exception
 */
class CriticalModuleException extends CriticalException
{
    /**
     * CriticalModuleException constructor.
     *
     * @param                 $message
     * @param string          $code
     */
    public function __construct(
        $message,
        $code = 'CRITICAL_ERROR'
    ) {
        parent::__construct($code . ": " . 'Модуль не установлен - ' . $message, $code);
    }
}