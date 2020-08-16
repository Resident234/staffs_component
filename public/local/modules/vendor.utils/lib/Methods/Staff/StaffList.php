<?php

namespace Vendor\Utils\Methods\Staff;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UI\PageNavigation;
use NotaTools\BitrixUtils;
use NotaTools\Exception\Iblock\ElementNotFoundException;
use NotaTools\Exception\Iblock\IblockException;
use NotaTools\Exception\Iblock\IblockNotFoundException;
use NotaTools\Exception\Iblock\IblockPropertyNotFoundException;
use NotaTools\Exception\Iblock\PropertyEnumNotFoundException;
use NotaTools\Exception\Iblock\SectionNotFoundException;
use NotaTools\Exception\User\UserNotFoundException;
use NotaTools\Helpers\TaggedCacheHelper;
use NotaTools\Utils;
use Vendor\Utils\Enum\Cache;
use Vendor\Utils\Enum\PropertyCode;
use Vendor\Utils\Enum\StaffEnum;
use Vendor\Utils\Enum\SortingEnum;
use Vendor\Utils\Exception\Filter\FacetException;
use Vendor\Utils\Exception\Rest\RestCriticalException;
use Vendor\Utils\Exception\Rest\RestCriticalModuleException;
use Vendor\Utils\Exception\Rest\RestFatalException;
use Vendor\Utils\Exception\Rest\RestFatalIblockException;
use Vendor\Utils\Exception\Rest\RestFatalPropertyException;
use Vendor\Utils\Exception\Rest\RestNotFoundException;
use Vendor\Utils\Exception\Rest\RestValidateException;
use Vendor\Utils\Filter\FacetFilter;
use Vendor\Utils\Helpers\FilterHelper;
use Vendor\Utils\Helpers\LoaderHelper;
use Vendor\Utils\Helpers\SortHelper;
use Vendor\Utils\Orm\Collection\StaffCollection;
use Vendor\Utils\Orm\Entity\Staff as StaffEntity;
use Vendor\Utils\Orm\Entity\Positions as PositionsEntity;
use Vendor\Utils\Orm\Tables\PositionsTable;
use Vendor\Utils\Orm\Tables\StaffTable;
use Vendor\Utils\Service\Cache\CacheListService;
use Vendor\Utils\Service\UserService;

/**
 * Class StaffList
 * @package Vendor\Utils\Methods\Staff
 */
class StaffList extends Staff
{
    /**
     * @param array        $params
     *
     * @param              $start
     *
     * @return array
     * @throws RestCriticalException
     * @throws RestCriticalModuleException
     * @throws RestFatalException
     * @throws RestFatalIblockException
     * @throws RestFatalPropertyException
     * @throws RestNotFoundException
     */
    public static function list(array $params): array
    {
        try {
            LoaderHelper::includeModuleIblock();
            $params = array_change_key_case($params, CASE_UPPER);
            if ((int)$params['LIMIT'] === 0) {
                $params['LIMIT'] = static::DEFAULT_LIMIT;
            } else {
                $params['LIMIT'] = (int)$params['LIMIT'];
            }
            if ((int)$params['PAGE'] === 0) {
                $params['PAGE'] = static::DEFAULT_PAGE;
            } else {
                $params['PAGE'] = (int)$params['PAGE'];
            }
            $result = [];
            $filter = $params['FILTER'];
            Utils::eraseArray($filter);
            $filter = array_change_key_case($filter, CASE_UPPER);
            if (!empty($params['SORT'])) {
                $sort = array_change_key_case($params['SORT'], CASE_UPPER);
            } else {
                $sort = static::DEFAULT_SORT;
            }

            $pagination = new PageNavigation(static::PAGINATION_NAME);
            $pagination->setPageSize($params['LIMIT']);
            $pagination->initFromUri();
            $page = (int)$pagination->getCurrentPage();

            $useCache = SortHelper::isDefaultSort($sort, static::DEFAULT_SORT) && empty($filter);
            if ($useCache) {
                try {
                    $instance = Application::getInstance();
                    $cache = $instance->getCache();
                } catch (SystemException $e) {
                    throw new RestCriticalException($e->getMessage());
                }
                $cacheDir = CacheListService::getListCacheDir(static::CACHE_PREFIX);
                $cacheUniqueString = CacheListService::getListCacheUniqueString(static::CACHE_PREFIX, $page);
                $cacheTag = CacheListService::getListCacheTagId(static::CACHE_PREFIX);
                if ($cache->initCache(Cache::MONTH, $cacheUniqueString, $cacheDir)) {
                    $vars = $cache->getVars();
                    $result = $vars['RES'];
                } elseif ($cache->startDataCache()) {
                    $tagCache = new TaggedCacheHelper($cacheDir);
                    $result = static::listExecute($filter, $sort, $pagination);
                    $tagCache->addTag($cacheTag);
                    $tagCache->end();
                    $cache->endDataCache(['RES' => $result]);
                }
            } else {
                $result = static::listExecute($filter, $sort, $pagination);
            }
        } catch (ArgumentException $e) {
            throw new FatalException($e->getMessage());
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        } catch (LoaderException $e) {
            throw new CriticalModuleException($e->getMessage());
        }
        return $result;
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws RestCriticalException
     * @throws RestFatalException
     * @throws RestFatalIblockException
     * @throws RestFatalPropertyException
     * @throws RestValidateException
     */
    public static function listFilter(array $params): array
    {
        try {
            if ($params === null) {
                $params = [];
            }
            if (!is_array($params)) {
                throw new ValidateException('фильтр не массив');
            }
            $params = array_change_key_case($params, CASE_UPPER);
            Utils::eraseArray($params);
        
            $query = PositionsTable::query();
            $query->setSelect(PositionsEntity::getSelect())->countTotal(true);
            $query->setOrder(PositionsEntity::getOrder());
            $res = $query->exec();
            $collection = $res->fetchCollection();
            /** @var PositionsCollection $collection */
            $positionsList = $collection->toArrayFormattedFilterList();
            $result = array(
                0 =>
                    array(
                        'id' => 'FIO',
                        'name' => 'ФИО',
                        'default' => true,
                    ),
                1 =>
                    array(
                        'id' => 'HIRING_DATE',
                        'name' => 'Дата принятия на работу',
                        'type' => 'date',
                        'default' => true,
                    ),
                2 =>
                    array(
                        'id' => 'POSITION',
                        'name' => 'Должность',
                        'type' => 'list',
                        'items' => $positionsList,
                        'default' => true,
                    ),
            );

            return $result;
        } catch (ArgumentException $e) {
            throw new FatalException($e->getMessage());
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        }
    }

    /**
     * @param array $filter
     *
     * @return ConditionTree|null
     * @throws ArgumentException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws IblockPropertyNotFoundException
     * @throws PropertyEnumNotFoundException
     * @throws SectionNotFoundException
     * @throws SystemException
     */
    protected static function prepareListFilter(array $filter): ?ConditionTree
    {
        $useFilter = false;
        if (!empty($filter)) {
            $condition = new ConditionTree();
            if (trim($filter['FIO']) && in_array('FIO', StaffEntity::getFilterFields(), true)) {
                $condition->whereLike('FIO', '%' . trim($filter['FIO']) . '%');
                $useFilter = true;
            }
            if (trim($filter['HIRING_DATE_FROM']) && trim($filter['HIRING_DATE_TO'])) {
                $objHiringDateFrom = new DateTime(trim($filter['HIRING_DATE_FROM']), "d.m.Y H:i:s");
                $objHiringDateTo = new DateTime(trim($filter['HIRING_DATE_TO']), "d.m.Y H:i:s");
                $condition->where('HIRING_DATE', '<=', $objHiringDateTo)
                    ->where('HIRING_DATE', '>=', $objHiringDateFrom);
                $useFilter = true;
            }
            if (trim($filter['POSITION'])) {
                $condition->where('POSITION_ID', trim($filter['POSITION']));
                $useFilter = true;
            }

        }
        return $useFilter ? $condition : null;
    }

    /**
     * @param array $params
     *
     * @param array $filter
     *
     * @param array $sort
     *
     * @param PageNavigation $pagination
     * @return array
     * @throws ArgumentException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws IblockPropertyNotFoundException
     * @throws ObjectPropertyException
     * @throws PropertyEnumNotFoundException
     * @throws SectionNotFoundException
     * @throws SystemException
     */
    protected static function listExecute(array $filter, array $sort, PageNavigation $pagination): array
    {
        $filterNew = null;
        $query = StaffTable::query();
        $query->setSelect(StaffEntity::getSelect())->countTotal(true);
        if (!empty($filter)) {
            $filterNew = static::prepareListFilter($filter);
        }
        $result = [
            'PAGE'  => $pagination->getCurrentPage(),
            'LIMIT' => $pagination->getLimit(),
        ];
        if (!empty($filter) && $filterNew !== null) {
            /** @var Query $query */
            $query->where($filterNew);
        }
        if (!empty($sort)) {
            $query->setOrder($sort);
        }
        $query->setLimit($pagination->getLimit());
        $query->setOffset($pagination->getOffset());
        $res = $query->exec();
        $countAll = (int)$res->getCount();
        $pagination->setRecordCount($countAll);
        $collection = $res->fetchCollection();
        /** @var StaffCollection $collection */
        $result['LIST'] = $collection->toArrayFormatted();
        $result['COUNT_ALL'] = $countAll;
        $result['COUNT'] = $res->getSelectedRowsCount();
        $result['PAGE_COUNT'] = 0;
        if ($countAll > 0) {
            $result['PAGE_COUNT'] = (int)$pagination->getPageCount();
        }
        if (($result['PAGE_COUNT'] > 0) && $result['PAGE_COUNT'] > (int)$result['PAGE']) {
            $result['next'] = (int)$result['PAGE'] + 1;
        }
        $result['total'] = $result['COUNT_ALL'];
        $result['NAV_OBJECT'] = $pagination;
        return $result;
    }

}
