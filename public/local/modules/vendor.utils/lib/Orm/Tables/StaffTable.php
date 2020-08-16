<?php

namespace Vendor\Utils\Orm\Tables;

use Bitrix\Main\{ArgumentException,
    ORM\Fields\DateField,
    ORM\Fields\Relations\Reference,
    ORM\Fields\StringField,
    ORM\Query\Join,
    SystemException};
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Vendor\Utils\Orm\Collection\StaffCollection;

/**
 * Class StaffTable
 * @package Vendor\Utils\Orm\Tables
 */
class StaffTable extends DataManager
{
    /**
     * @inheritDoc
     */
    public static function getCollectionClass()
    {
        return StaffCollection::class;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'vendor_staff';
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     */
    public static function getMap()
    {
        return [
            'ID'            => new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            'FIO'           => new StringField('FIO', ['required' => true]),
            'HIRING_DATE'   => new DateField('HIRING_DATE', ['required' => true]),
            'POSITION_ID'   => new IntegerField('POSITION_ID', ['required' => true]),
            'POSITION'      => new Reference('POSITION', PositionsTable::getEntity(), Join::on('this.POSITION_ID', 'ref.ID')),
        ];
    }
}