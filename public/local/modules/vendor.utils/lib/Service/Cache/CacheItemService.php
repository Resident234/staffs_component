<?php

namespace Vendor\Utils\Service\Cache;

use NotaTools\Helpers\TaggedCacheHelper;

/**
 * Class CacheItemService
 * @package Vendor\Utils\Service\Cache
 */
class CacheItemService extends CacheServiceBase
{

    public static function getItemCacheDir(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'detail'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getItemCacheUniqueString(string $prefix, int $id, array $additionalStrings = []): string
    {
        $strings = [$prefix, $id];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    /**
     * @param string $prefix
     * @param        $id
     */
    public static function clearItemCache(string $prefix, $id): void
    {
        $tags = [
            static::getItemCacheUniqueString($prefix, $id),
        ];
        TaggedCacheHelper::clearManagedCache($tags);
    }
}