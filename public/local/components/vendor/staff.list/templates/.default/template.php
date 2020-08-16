<?php
use Vendor\Utils\Enum\StaffEnum;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<?php if ($arResult['LIST']): ?>
<?php
    $arGridParams = array(
        'EXTRANET_SITE' => 'N',
        'GRID_ID' => 'STAFF',
        'FILTER_ID' => 'STAFF',

        'HEADERS' => [
            [
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'default' => true,
                'editable' => false,
            ],
            [
                'id' => 'FIO',
                'name' => 'Имя и фамилия',
                'sort' => 'FIO',
                'default' => true,
                'editable' => false,
            ],
            [
                'id' => 'HIRING_DATE',
                'name' => 'Дата принятия на работу',
                'sort' => 'HIRING_DATE',
                'default' => true,
                'editable' => false,
            ],
            [
                'id' => 'POSITION',
                'name' => 'Должность',
                'sort' => 'POSITION',
                'default' => true,
                'editable' => false,
            ]
        ],
        'PROCESS_EXTRANET' => 'Y',
        'ROWS_COUNT' => 2,
        'TOOLBAR_MENU' =>
            array(
                0 =>
                    array(
                        'TYPE' => 'EXPORT_EXCEL',
                        'TITLE' => 'Экспортировать в Excel',
                        'LINK' => ''
                    ),
                1 =>
                    array(
                        'TYPE' => 'SYNC_OUTLOOK',
                        'TITLE' => 'Синхронизировать с Outlook',
                        'LINK' => 'javascript:BX.StsSync.sync(\'contacts\', \'/stssync/contacts\', \'/company/\', \'Notamedia\', \'Сотрудники\', \'{01825a4e-4eb0-ce2a-d5ea-9ba4117fa4cb}\', 443)',
                    ),
                2 =>
                    array(
                        'TYPE' => 'SYNC_CARDDAV',
                        'TITLE' => 'Синхронизировать с CardDAV',
                        'LINK' => 'javascript:(new BX.CDialog({\'content_url\':\'/bitrix/groupdav.php?lang=ru&help=Y&dialog=Y&siteTemplateId=bitrix24\',\'width\':\'\',\'height\':\'\'})).Show()',
                    ),
            ),
        'TOOLBAR_BUTTONS' =>
            array(
                0 =>
                    array(
                        'TYPE' => 'ADD',
                        'LINK' => 'javascript:B24.Bitrix24InviteDialog.ShowForm({\'MESS\':{\'BX24_INVITE_TITLE_INVITE\':\'Пригласите коллег\',\'BX24_INVITE_TITLE_ADD\':\'Добавить сотрудника\',\'BX24_INVITE_BUTTON\':\'Пригласить\',\'BX24_CLOSE_BUTTON\':\'Закрыть\',\'BX24_LOADING\':\'Загрузка...\'}})',
                        'TITLE' => 'Пригласить сотрудников',
                    ),
            ),
    );

    $rows = [];
    foreach ($arResult['LIST'] as $item) {
        $rows[] = [
            'id' => $item['ID'],
            'data' => [
                'CAN_DELETE' => false,
                'CAN_EDIT' => false,
                'ID' => $item['ID'],
                'FIO' => $item['FIO'],
                'HIRING_DATE' => $item['HIRING_DATE'],
                'POSITION' => $item['POSITION'],
            ],
            'columns' => [],
            'editable' => false,
            'columnClasses' => [],
            'actions' =>
                array(
                    0 =>
                        array(
                            'TITLE' => StaffEnum::VIEW_NAME,
                            'TEXT' => StaffEnum::VIEW_NAME,
                            'ONCLICK' => "window.location='/staff/" . $item['ID'] . "/'",
                            'DEFAULT' => true,
                        ),
                    1 =>
                        array(
                            'TITLE' => StaffEnum::EDIT_NAME,
                            'TEXT' => StaffEnum::EDIT_NAME,
                            'ONCLICK' => "window.location='/staff/" . $item['ID'] . "/edit/'",
                            'DEFAULT' => true,
                        ),
                )
        ];
    }
    $arGridParams['ROWS'] = $rows;
    $arGridParams['NAV_OBJECT'] = $arResult['NAV_OBJECT'];
    $arGridParams['FILTER'] = $arResult['FILTER'];
    $APPLICATION->includeComponent(
        'bitrix:main.ui.filter',
        '',
        [
            'FILTER_ID' => $arGridParams['FILTER_ID'],
            'GRID_ID' => $arGridParams['GRID_ID'],
            'FILTER' => $arGridParams['FILTER'],
            'FILTER_PRESETS' => $arGridParams['FILTER_PRESETS'],
            'ENABLE_LABEL' => true,
            'ENABLE_LIVE_SEARCH' => true,
            'RESET_TO_DEFAULT_MODE' => true
        ],
        $this->__component,
        ['HIDE_ICONS' => true]
    );

    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        [
            'GRID_ID' => $arGridParams['GRID_ID'],
            'HEADERS' => $arGridParams['HEADERS'],
            'ROWS' => $arGridParams['ROWS'],
            'NAV_OBJECT' => $arGridParams['NAV_OBJECT'],
            'TOTAL_ROWS_COUNT' => $arGridParams['ROWS_COUNT'],
            'ACTION_ALL_ROWS' => false,
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_MODE' => 'Y',
            'SHOW_ROW_CHECKBOXES' => false,
            'SHOW_SELECTED_COUNTER' => false,
            'EDITABLE' => false
        ],
        $this->__component
    );
    ?>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>
