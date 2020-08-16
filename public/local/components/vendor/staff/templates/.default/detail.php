<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\UI\Extension;

Extension::load("ui.buttons");
?>
<?$APPLICATION->IncludeComponent(
    'vendor:staff.detail',
    '',
    [
        'ID' => $arResult['VARIABLES']['ID']
    ]
);?>
<?php require_once '__list_button.php'; ?>