<?php

namespace Vendor\Utils\Service\Cache;

use NotaTools\Helpers\TaggedCacheHelper;

/**
 * Class CacheService
 * @package Vendor\Utils\Service\Cache
 */
class CacheListService extends CacheServiceBase
{
    public static function getListCacheDir(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'list'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function geFilterCacheDir(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'filter'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getListCacheUniqueString(string $prefix, int $page = 1, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'list', $page];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    public static function getListCacheTagId(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'list'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    public static function getFilterCacheUniqueString(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'filter'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    /**
     * @param string $prefix
     */
    public static function clearListCache(string $prefix): void
    {
        $tags = [
            static::getListCacheTagId($prefix),
            static::getFilterCacheUniqueString($prefix),
        ];
        TaggedCacheHelper::clearManagedCache($tags);
    }
}