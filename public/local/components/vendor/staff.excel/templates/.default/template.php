<?php use Bitrix\Main\UI\PageNavigation;

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
<html>
<head>
    <title>Export</title>
    <meta http-equiv="Content-Type" content="text/html; charset='UTF-8">
    <style>
        td {
            mso-number-format: \@;
        }

        .number0 {
            mso-number-format: 0;
        }

        .number2 {
            mso-number-format: Fixed;
        }
    </style>
</head>
<body>
<table border="1">
    <tr>
        <? foreach (['ID', 'ФИО', 'Дата принятия на работу', 'Должность'] as $headItem): ?>
            <td><?= $headItem; ?></td>
        <? endforeach; ?>
    </tr>
    <? foreach ($arResult['LIST'] as $listItem): ?>
        <tr>
            <td><?= $listItem['ID']; ?></td>
            <td><?= $listItem['FIO']; ?></td>
            <td><?= $listItem['HIRING_DATE']; ?></td>
            <td><?= $listItem['ID']; ?></td>
        </tr>
    <? endforeach; ?>
</table>
</body>
</html>
