<?php

namespace Vendor\Utils\Service\Cache;

/**
 * Class CacheService
 * @package Vendor\Utils\Service\Cache
 */
class CacheService extends CacheServiceBase
{
    public static function getStatisticCacheDir(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'statistic'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getGroupStatisticCacheDir(array $additionalStrings = []): string
    {
        return static::getStatisticCacheDir('group', $additionalStrings);
    }

    public static function getScopeStatisticCacheDir(array $additionalStrings = []): string
    {
        return static::getStatisticCacheDir('scope', $additionalStrings);
    }

    public static function getPulseStatisticCacheDir(array $additionalStrings = []): string
    {
        return static::getStatisticCacheDir('pulse', $additionalStrings);
    }

    public static function getUserStatisticCacheDir(array $additionalStrings = []): string
    {
        return static::getStatisticCacheDir('user', $additionalStrings);
    }

    public static function getUserCacheDir(array $additionalStrings = []): string
    {
        $strings = ['user'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getScopeCacheDir(array $additionalStrings = []): string
    {
        $strings = ['scope'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getGroupCacheDir(array $additionalStrings = []): string
    {
        $strings = ['group'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheDir($strings);
    }

    public static function getStatisticCacheUniqueString(string $prefix, array $additionalStrings = []): string
    {
        $strings = [$prefix, 'statistic'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    public static function getGroupStatisticCacheUniqueString(array $additionalStrings = []): string
    {
        return static::getStatisticCacheUniqueString('group', $additionalStrings);
    }

    public static function getScopeStatisticCacheUniqueString(array $additionalStrings = []): string
    {
        return static::getStatisticCacheUniqueString('scope', $additionalStrings);
    }

    public static function getPulseStatisticCacheUniqueString(array $additionalStrings = []): string
    {
        return static::getStatisticCacheUniqueString('pulse', $additionalStrings);
    }

    public static function getUserStatisticCacheUniqueString(array $additionalStrings = []): string
    {
        return static::getStatisticCacheUniqueString('user', $additionalStrings);
    }

    public static function getUserCacheUniqueString(array $additionalStrings = []): string
    {
        $strings = ['user'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    public static function getGroupCacheUniqueString(array $additionalStrings = []): string
    {
        $strings = ['group'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }

    public static function getScopeCacheUniqueString(array $additionalStrings = []): string
    {
        $strings = ['scope'];
        $strings = static::mergeAdditionalStrings($strings, $additionalStrings);
        return static::getCacheUniqueString($strings);
    }
}