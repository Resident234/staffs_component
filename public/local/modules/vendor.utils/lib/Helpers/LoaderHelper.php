<?php

namespace Vendor\Utils\Helpers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

/**
 * Class LoaderHelper
 * @package Vendor\Utils\Helpers
 */
class LoaderHelper
{
    /**
     * @throws LoaderException
     */
    public static function includeModuleIblock(): void
    {
        static::includeModule('iblock');
    }

    /**
     * @param string $module
     *
     * @throws LoaderException
     */
    public static function includeModule(string $module): void
    {
        if (!Loader::includeModule($module)) {
            throw new LoaderException('модуль ' . $module . ' не загружен');
        }
    }
}