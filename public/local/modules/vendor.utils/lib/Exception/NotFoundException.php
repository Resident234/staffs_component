<?php

namespace Vendor\Utils\Exception;

use Bitrix\Rest\RestException;
use CRestServer;
use Exception;

/**
 * Class NotFoundException
 * @package Vendor\Utils\Exception
 */
class NotFoundException extends Exception
{
    /**
     * NotFoundException constructor.
     *
     * @param                 $message
     * @param string $code
     */
    public function __construct(
        $message = '',
        $code = 'NOT_FOUND'
    ) {
        parent::__construct($code . ": " . $message);
    }
}