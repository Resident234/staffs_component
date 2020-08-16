<?php

namespace Vendor\Utils\Helpers;

/**
 * Class SortHelper
 * @package Vendor\Utils\Helpers
 */
class SortHelper
{
    /**
     * @param $sort
     * @param $defaultSort
     *
     * @return bool
     */
    public static function isDefaultSort($sort, $defaultSort): bool
    {
        return !empty(array_uintersect_assoc($sort, $defaultSort, 'strcasecmp'));
    }
}