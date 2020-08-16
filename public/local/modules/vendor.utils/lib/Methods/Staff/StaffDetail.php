<?php

namespace Vendor\Utils\Methods\Staff;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use NotaTools\Helpers\TaggedCacheHelper;
use Vendor\Utils\Enum\Cache;
use Vendor\Utils\Enum\UserGroupRoles;
use Vendor\Utils\Exception\CriticalException;
use Vendor\Utils\Exception\CriticalModuleException;
use Vendor\Utils\Exception\NotFoundException;
use Vendor\Utils\Exception\FatalException;
use Vendor\Utils\Helpers\LoaderHelper;
use Vendor\Utils\Helpers\RestHelper;
use Vendor\Utils\Orm\Entity\Staff as StaffEntity;
use Vendor\Utils\Orm\Tables\StaffTable;
use Vendor\Utils\Service\Cache\CacheItemService;
use Vendor\Utils\Service\UserService;

/**
 * Class StaffDetail
 * @package Vendor\Utils\Methods\Staff
 */
class StaffDetail extends Staff
{
    protected const CACHE_PREFIX = 'staff';

    /**
     * @param array $params
     *
     * @return array
     * @throws RestCriticalException
     * @throws RestCriticalModuleException
     * @throws RestFatalException
     * @throws RestFatalIblockException
     * @throws RestFatalPropertyException
     * @throws RestNotFoundException
     * @throws RestValidateException
     */
    public static function get(array $params): array
    {
        try {
            LoaderHelper::includeModuleIblock();
            $params = array_change_key_case($params, CASE_UPPER);
            $id = (int)$params['ID'];
            if ($id === 0) {
                throw new ArgumentException('не задан ID');
            }
            $res = [];
            $instance = Application::getInstance();
            $cache = $instance->getCache();
            $cacheDir = CacheItemService::getItemCacheDir(static::CACHE_PREFIX);
            $cacheTag = $cacheUniqueString = CacheItemService::getItemCacheUniqueString(static::CACHE_PREFIX, $id);
            if ($cache->initCache(Cache::MONTH, $cacheUniqueString, $cacheDir)) {
                $vars = $cache->getVars();
                $res = $vars['RES'];
            } elseif ($cache->startDataCache()) {
                $tagCache = new TaggedCacheHelper($cacheDir);
                /** @var StaffEntity $className */
                $className = static::ENTITY;
                $el = StaffTable::getByPrimary($id, ['select' => StaffEntity::getBaseSelect()])->fetchObject();
                if ($el === null) {
                    throw new NotFoundException('Элемент не найден');
                }
                /** @var StaffEntity $staff */
                $staff = new $className($el);
                $res = $staff->toArrayFormattedDetail();
                $tagCache->addTag($cacheTag);
                $tagCache->end();
                $cache->endDataCache(['RES' => $res]);
            }
            return $res;
        } catch (ArgumentException $e) {
            throw new FatalException($e->getMessage());
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        } catch (LoaderException $e) {
            throw new CriticalModuleException($e->getMessage());
        }
    }

}
