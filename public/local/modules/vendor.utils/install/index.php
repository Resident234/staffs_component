<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Type\DateTime;
use Vendor\Utils\Orm\Tables\PositionsTable;
use Vendor\Utils\Orm\Tables\StaffTable;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
if (class_exists('vendor_utils')) {
    return;
}

/**
 * Class vendor_utils
 */
class vendor_utils extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $this->MODULE_ID = 'vendor.utils';
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2020-08-09 16:42:14';
        $this->MODULE_NAME = 'Модуль ';
        $this->MODULE_DESCRIPTION = '';
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';
    }

    public function doInstall()
    {
        $connection = Application::getConnection();

        $arPositionsIDs = [];
        if(!$connection->isTableExists(PositionsTable::getTableName())) {
            PositionsTable::getEntity()->createDbTable();
            $rows = [
                ['NAME' => 'Старший консультант', 'DESCRIPTION' => ''],
                ['NAME' => 'Ведущий консультант', 'DESCRIPTION' => ''],
                ['NAME' => 'Старший консультант-разработчик', 'DESCRIPTION' => ''],
                ['NAME' => 'Руководитель проектов', 'DESCRIPTION' => ''],
                ['NAME' => 'Администратор проектов', 'DESCRIPTION' => ''],
                ['NAME' => 'Руководитель службы', 'DESCRIPTION' => ''],
                ['NAME' => 'Ведущий системный архитектор', 'DESCRIPTION' => ''],
                ['NAME' => 'Старший эксперт', 'DESCRIPTION' => ''],
                ['NAME' => 'Начальник отдела', 'DESCRIPTION' => ''],
                ['NAME' => 'Специалист', 'DESCRIPTION' => ''],
                ['NAME' => 'Директор сегмента', 'DESCRIPTION' => ''],
                ['NAME' => 'Заместитель генерального директора', 'DESCRIPTION' => ''],
                ['NAME' => 'Менеджер по персоналу', 'DESCRIPTION' => ''],
                ['NAME' => 'Старший аналитик', 'DESCRIPTION' => ''],
                ['NAME' => 'Администратор', 'DESCRIPTION' => ''],
            ];
            foreach ($rows as $row) {
                $result = PositionsTable::add($row);
                if ($result->isSuccess()) {
                    $arPositionsIDs[] = $result->getId();
                }
            }

        }

        if(!$connection->isTableExists(StaffTable::getTableName())) {
            StaffTable::getEntity()->createDbTable();
            $rows = [
                [
                    'FIO' => 'Семушкина Анастасия Сергеевна',
                    'HIRING_DATE' => '13.07.2020',
                ],[
                    'FIO' => 'Даньшин Михаил Сергеевич',
                    'HIRING_DATE' => '14.07.2020',
                ],[
                    'FIO' => 'Самарокова Елена Ивановна',
                    'HIRING_DATE' => '01.07.2020',
                ],[
                    'FIO' => 'Гаврилина Евгения Васильевна',
                    'HIRING_DATE' => '02.07.2020',
                ],[
                    'FIO' => 'Осинцев Виктор Викторович',
                    'HIRING_DATE' => '04.08.2020',
                ],[
                    'FIO' => 'Белковский Дмитрий Викторович',
                    'HIRING_DATE' => '10.08.2020',
                ],[
                    'FIO' => 'Куревлев Сергей Васильевич',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Павликова Анастасия Николаевна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Савицкая Ольга Николаевна',
                    'HIRING_DATE' => '29.07.2020',
                ],[
                    'FIO' => 'Смирнов Сергей Владимирович',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Тюляков Сергей Вячеславович',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Устинова Мария Ивановна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Кряжевских Сергей Владимирович',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Филимонова Алина Викторовна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Чувилина Мария Дмитриевна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Соколов Александр Николаевич',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Саксин Александр Валерьевич',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Глушко Екатерина Владимировна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Карпухина Анастасия Валериевна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Хвостова Елена Александровна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Свояк Жанна Александровна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Носенко Виктор Анатольевич',
                    'HIRING_DATE' => '29.07.2020',
                ],[
                    'FIO' => 'Строганова Ирина Сергеевна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Морозов Иван Николаевич',
                    'HIRING_DATE' => '15.07.2020',
                ],[
                    'FIO' => 'Насыров Виктор Владимирович',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Узякаев Марат Александрович',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Михотин Дмитрий Валерьевич',
                    'HIRING_DATE' => '30.07.2020',
                ],[
                    'FIO' => 'Милованов Андрей Вячеславович',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Кучеренко Александр Михайлович',
                    'HIRING_DATE' => '29.07.2020',
                ],[
                    'FIO' => 'Кондратьев Алексей Евгеньевич',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Деревенчук Эльвира Гависовна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Заглядкина Оксана Александровна',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Алешин Леонид Евгеньевич',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Зубко Дмитрий Васильевич',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Черняков Евгений Вадимович',
                    'HIRING_DATE' => '28.07.2020',
                ],[
                    'FIO' => 'Боргоякова Ольга Алексеевна',
                    'HIRING_DATE' => '30.07.2020',
                ],[
                    'FIO' => 'Бахвалов Борис Сергеевич',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Зиновьева Юлия Рафиковна',
                    'HIRING_DATE' => '27.07.2020',
                ],[
                    'FIO' => 'Даньшин Михаил Сергеевич',
                    'HIRING_DATE' => '05.08.2020',
                ],[
                    'FIO' => 'Самарокова Елена Ивановна',
                    'HIRING_DATE' => '04.08.2020',
                ]
            ];
            foreach ($rows as $row) {
                $row['HIRING_DATE'] = new DateTime($row['HIRING_DATE'], 'd.m.Y');
                $row['POSITION_ID'] = $arPositionsIDs[array_rand($arPositionsIDs)];
                StaffTable::add($row);
            }
        }

        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function doUninstall()
    {
        $connection = Application::getConnection();
        if($connection->isTableExists(StaffTable::getTableName())) {
            $connection->dropTable(StaffTable::getTableName());
        }
        if($connection->isTableExists(PositionsTable::getTableName())) {
            $connection->dropTable(PositionsTable::getTableName());
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

}