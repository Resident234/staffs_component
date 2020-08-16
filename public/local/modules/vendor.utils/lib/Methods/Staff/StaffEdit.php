<?php

namespace Vendor\Utils\Methods\Staff;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use NotaTools\BitrixUtils;
use NotaTools\Exception\Iblock\IblockException;
use NotaTools\Exception\Iblock\IblockNotFoundException;
use NotaTools\Utils;
use NotaTools\Helpers\TaggedCacheHelper;
use Rakit\Validation\RuleNotFoundException;
use Rakit\Validation\RuleQuashException;
use Rakit\Validation\Validator;
use Vendor\Utils\Enum\Action\ActionBaseEnum;
use Vendor\Utils\Enum\PropertyCode;
use Vendor\Utils\Enum\UserGroupRoles;
use Vendor\Utils\Exception\CriticalException;
use Vendor\Utils\Exception\FatalException;
use Vendor\Utils\Exception\FatalSaveException;
use Vendor\Utils\Exception\NotFoundException;
use Vendor\Utils\Exception\ValidateException;
use Vendor\Utils\Helpers\LoaderHelper;
use Vendor\Utils\Orm\Entity\Staff as StaffEntity;
use Vendor\Utils\Orm\Tables\EO_Positions;
use Vendor\Utils\Orm\Tables\EO_Positions_Collection;
use Vendor\Utils\Orm\Tables\Iblock\EO_Scope_Collection;
use Vendor\Utils\Orm\Tables\PositionsTable;
use Vendor\Utils\Orm\Tables\StaffTable;
use Vendor\Utils\Methods\User\User;
use Vendor\Utils\Service\Cache\CacheListService;
use Vendor\Utils\Service\StaffService;
use Vendor\Utils\Service\UserService;

/**
 * Class StaffEdit
 * @package Vendor\Utils\Methods\Staff
 */
class StaffEdit extends Staff
{

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
     * @throws RestAccessException
     */
    public static function editForm(array $params): array
    {
        LoaderHelper::includeModuleIblock();
        $params = array_change_key_case($params, CASE_UPPER);
        Utils::eraseArray($params);
        $validateRules = [
            'ID' => [
                'integer',
                'min:1',
                function ($value) {
                    return (int)$value > 0 ? ElementTable::getById((int)$value)->getSelectedRowsCount() === 1 : true;
                },
            ],
        ];
        if(isset($params['TITLE'])){
            $params['NAME'] = $params['TITLE'];
            unset($params['TITLE']);
        }
        $validator = new Validator([
            'ID' => 'Форма не найдена'
        ]);
        $id = (int)$params['ID'];
        static::validate($validator, $params, $validateRules);
        try {
            /** @var StaffEntity $className */
            $className = static::ENTITY;
            if ($id > 0) {
                $el = StaffTable::getByPrimary($id, ['select' => StaffEntity::getBaseSelect()])->fetchObject();
                if ($el === null) {
                    throw new NotFoundException('Элемент не найден');
                }
            } else {
                $el = null;
            }
            /** @var StaffEntity $staff */
            $staff = new $className($el);
            return $staff->toArrayFormattedEdit();
        } catch (ArgumentException $e) {
            throw new FatalException($e->getMessage());
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        }
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws RestCriticalException
     * @throws RestFatalException
     * @throws RestFatalPropertyException
     * @throws RestNotFoundException
     * @throws RestFatalIblockException
     * @throws RestFatalSaveException
     * @throws RestValidateException
     * @throws RestCriticalModuleException
     * @throws RestAccessException
     */
    public static function edit(array $params): array
    {
        LoaderHelper::includeModuleIblock();
        $params = array_change_key_case($params, CASE_UPPER);
        if ($params['ID'] === null || (is_string($params['ID']) && empty($params['ID']))) {
            $id = 0;
        } else {
            if (is_numeric($params['ID'])) {
                $id = (int)$params['ID'];
                if ($id < 0) {
                    throw new ValidateException('ID указан не верно');
                }
            } else {
                throw new ValidateException('ID указан не верно');
            }
        }
        $fields = array_change_key_case($params['FIELDS'], CASE_UPPER);
        Utils::eraseArray($fields);
        $validator = new Validator;
        try {
            static::validate($validator, $fields, static::getEditValidateRules($validator), static::getEditValidateMessage());
        } catch (RuleNotFoundException|RuleQuashException $e) {
            throw new ValidateException($e->getMessage() . ' - ' . $e->getCode());
        } catch (ArgumentException $e) {
            throw new FatalException($e->getMessage());
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        }
        try {
            $className = static::ENTITY;
            /** @var StaffEntity $staff */
            $staff = $id > 0 ? new $className($id) : new $className();
            $staff->setData($fields);
            $res = $staff->save();
            if (!$res->isSuccess()) {
                throw new FatalSaveException(BitrixUtils::extractErrorMessage($res));
            }
            TaggedCacheHelper::clearManagedCache([
                CacheListService::getListCacheTagId('staff')
            ]);
            return ['status' => true, 'id' => $staff->getId(), 'redirect_url' => $staff->getListUrl()];
        } catch (SystemException $e) {
            throw new CriticalException($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    protected static function getEditValidateMessage(): array
    {
        return [
            'required'                      => ':attribute не заполнен',
            'numeric'                       => ':attribute должно быть числом',
            'FIO:min'                       => 'Заголовок должен быть не менее 3 символов',
            'FIO:max'                       => 'Заголовок должен быть не более 120 символов',
            'HIRING_DATE:bitrix_date_time'  => 'Некорректный формат даты',
            'POSITION_ID'                   => 'Некорректная должность'
        ];
    }

    /**
     * @inheritDoc
     *
     * @param Validator $validator
     * @param array     $fields
     *
     * @return array
     * @throws ArgumentException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws ObjectPropertyException
     * @throws RuleNotFoundException
     * @throws RuleQuashException
     * @throws SystemException
     */
    protected static function getEditValidateRules(Validator $validator): array
    {
        $validators = [
            'FIO'               => 'required|min:3|max:120',
            'HIRING_DATE'       => 'required|min:1',/** @todo bitrix_date_time valudator is not working */
        ];
        /** @var EO_Positions_Collection $collection */
        $query = PositionsTable::query();
        $collection = $query->setSelect(['ID'])->exec()->fetchCollection();
        if ($collection === null) {
            throw new RuleQuashException('нет элементов для проверки');
        }
        $validators['POSITION_ID'] = [
            'required',
            'min:1',
            $validator('in', $collection->getIdList()),
        ];
        return $validators;
    }

}
