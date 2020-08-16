<?php

namespace Vendor\Utils\Exception;

use CRestServer;
use Exception;

/**
 * Class FatalSaveException
 * @package Vendor\Utils\Exception
 */
class FatalSaveException extends FatalException
{
    /**
     * FatalSaveException constructor.
     *
     * @param                 $message
     * @param string          $code`
     */
    public function __construct(
        $message,
        $code = 'FATAL_ERROR'
    ) {
        parent::__construct($code . ": " . 'Ошибка сохранения - ' . $message, $code);
    }
}