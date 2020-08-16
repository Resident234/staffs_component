<?php

namespace Vendor\Utils\Orm\Tables;

use Bitrix\Main\{ArgumentException,
    ORM\Fields\StringField,
    SystemException};
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Vendor\Utils\Orm\Collection\PositionsCollection;

/**
 * Class PositionsTable
 * @package Vendor\Utils\Orm\Tables
 */
class PositionsTable extends DataManager
{
    /**
     * @inheritDoc
     */
    public static function getCollectionClass()
    {
        return PositionsCollection::class;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'vendor_positions';
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     */
    public static function getMap()
    {
        return [
            'ID'          => new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            'NAME'        => new StringField('NAME', ['required' => true]),
            'DESCRIPTION' => new TextField('DESCRIPTION')
        ];
    }
}