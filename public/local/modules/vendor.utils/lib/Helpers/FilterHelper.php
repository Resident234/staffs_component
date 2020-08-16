<?php

namespace Vendor\Utils\Helpers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use NotaTools\BitrixUtils;
use Vendor\Utils\Orm\Tables\Iblock\GroupsTable;

/**
 * Class FilterHelper
 * @package Vendor\Utils\Helpers
 */
class FilterHelper
{
    /**
     * @param string        $code
     * @param               $value
     *
     * @param ConditionTree $condition
     *
     * @throws ArgumentException
     */
    public static function setFilterVal(string $code, $value, ConditionTree $condition): void
    {
        if (!empty($code)) {
            $isNot = false;
            if (strpos($code, '!') !== false) {
                $isNot = true;
                $code = str_replace('!', '', $code);
            }
            if (is_array($value) && !empty($value)) {
                if ($isNot) {
                    $condition->whereNotIn($code, $value);
                } else {
                    $condition->whereIn($code, $value);
                }
            } else {
                if (is_string($value) || is_numeric($value)) {
                    $operation = '=';
                    if (strpos($code, '>=') === 0) {
                        $code = str_replace('>=', '', $code);
                        $operation = '>=';
                    } elseif (strpos($code, '<=') === 0) {
                        $code = str_replace('<=', '', $code);
                        $operation = '<=';
                    } elseif (strpos($code, '>') === 0) {
                        $code = ltrim($code, '>');
                        $operation = '>';
                    } elseif (strpos($code, '<') === 0) {
                        $code = ltrim($code, '<');
                        $operation = '<';
                    }
                    if ($isNot) {
                        $condition->whereNot($code, $operation, $value);
                    } else {
                        $condition->where($code, $operation, $value);
                    }
                } else if ($value === null) {
                    if ($isNot) {
                        $condition->whereNotNull($code);
                    } else {
                        $condition->whereNull($code);
                    }
                }
            }
        }
    }

    /**
     * @param array $params
     * @param array $allowedFields
     */
    public static function sanitizeFilterParams(array &$params, array $allowedFields): void
    {
        $newParams = [];
        array_walk($params, function(&$value, $key)	use ($allowedFields, &$newParams) {
            if (in_array($key, $allowedFields)) {
                $newParams[$key] = $value;
            }
        });
        //unset($newParams['LOGIC']);
        $params = $newParams;
        unset($newParams);
    }

    /**
     * @param $facetResult
     * @param $params
     * @throws ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function prepareFilterResult(&$facetResult, $params): void
    {
        /** @todo expand method */
        $idGroups = array_diff(array_column($facetResult['OPTIONS']['GROUP'], 'VALUE'), ['']);
        $groupId = (int)$params['GROUP'];
        if ($groupId && !in_array($groupId, $idGroups)) {
            $group = GroupsTable::getByPrimary($groupId)->fetchObject();
            if ($group !== null) {
                $facetResult['OPTIONS']['GROUP'][] = [
                    'NAME'        => $group->getName(),
                    'VALUE'       => $group->getId(),
                    'SELECTED'    => BitrixUtils::BX_BOOL_TRUE,
                    'NOT_RESULTS' => BitrixUtils::BX_BOOL_TRUE,
                ];
            }
        }
    }

}