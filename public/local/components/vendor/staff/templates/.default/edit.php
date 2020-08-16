<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}?>
<?$APPLICATION->IncludeComponent(
    'vendor:staff.edit',
    '',
    [
        'ID' => $arResult['VARIABLES']['ID'] ?? 0
    ]
);?>