<?php

namespace Vendor\Utils\Service\Cache;

/**
 * Class CacheServiceBase
 * @package Vendor\Utils\Service\Cache
 */
class CacheServiceBase
{
    /**
     * @param array $strings
     * @param       $additionalStrings
     *
     * @return array
     */
    public static function mergeAdditionalStrings(array $strings, $additionalStrings): array
    {
        if (!empty($additionalStrings)) {
            TrimArr($additionalStrings);
            if (!empty($additionalStrings)) {
                $strings = array_merge($strings, $additionalStrings);
            }
        }
        return $strings;
    }

    public static function getCacheDir(array $strings): string
    {
        return '/' . implode('_', $strings);
    }

    public static function getCacheUniqueString(array $strings): string
    {
        return implode('_', $strings);
    }
}