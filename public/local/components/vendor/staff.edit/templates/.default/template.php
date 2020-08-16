<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
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
<?php
$arPositionsOptions = $arResult['POSITION']['OPTIONS'];
$arTabs = [];
$arFieldsTmp = [];
$arFieldsTmp[] = [
    "id" => 'FIO',
    "name" => 'ФИО',
    "type" => "text",
    "value" => $arResult['FIO']
];
$arFieldsTmp[] = [
    "id" => 'HIRING_DATE',
    "name" => 'Дата принятия на работу',
    "type" => "date",
    "value" => $arResult['HIRING_DATE']
];

$arPositionsItems = [0 => 'not selected'];
$arPositionsItemsNames = array_column($arPositionsOptions, 'NAME');
$arPositionsItemsValues = array_column($arPositionsOptions, 'VALUE');
$arPositionsItems = $arPositionsItems + array_combine($arPositionsItemsValues, $arPositionsItemsNames);

$arFieldsTmp[] = [
    "id" => 'POSITION_ID',
    "name" => 'Должность',
    "type" => "select",
    'items' => $arPositionsItems,
    "value" => $arResult['POSITION']['VALUE']['VALUE']
];

$arTabs[] = array(
    "id" => "tab1", "name" => '', "title" => '', "icon" => "",
    "fields" => $arFieldsTmp
);

$APPLICATION->IncludeComponent(
    "bitrix:main.interface.form",
    "",
    array(
        "FORM_ID"               => 'STAFF',
        "TABS"                  => $arTabs,
        "BUTTONS"               => ["custom_html" => "", "standard_buttons" => true],
        "DATA"                  => $arResult["BP"],
        "THEME_GRID_ID"         => "user_grid",
        "SHOW_SETTINGS"         => "N",
        "AJAX_MODE"             => "N",
        "AJAX_OPTION_JUMP"      => "N",
        "AJAX_OPTION_STYLE"     => "Y",
    ),
    $component
);
?>
